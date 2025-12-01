<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\RajaOngkirService;
use App\Services\MidtransService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $rajaOngkirService;
    protected $midtransService;

    public function __construct(RajaOngkirService $rajaOngkirService, MidtransService $midtransService)
    {
        $this->rajaOngkirService = $rajaOngkirService;
        $this->midtransService = $midtransService;
    }

    /**
     * Membuat order dan transaksi Midtrans
     */
    public function createOrder(Request $request)
    {
        // Validasi input
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:products,id',
            'items.*.product_variant_id' => 'nullable|exists:product_variants,id',
            'items.*.quantity' => 'required|integer|min:1',
            'origin' => 'required',
            'destination' => 'required',
            'weight' => 'required|numeric',
            'courier' => 'required'
        ]);

        // Validasi kelengkapan profil user sebelum melanjutkan checkout
        $user = $request->user();
        if ($user) {
            $missing = [];
            foreach (['province_id', 'city_id', 'district_id'] as $field) {
                if (empty($user->{$field})) {
                    $missing[] = $field;
                }
            }
            // Tambahan yang umum diperlukan untuk pengiriman
            foreach (['address', 'phone'] as $field) {
                if (empty($user->{$field})) {
                    $missing[] = $field;
                }
            }

            if (!empty($missing)) {
                return response()->json([
                    'status' => 'error',
                    'code' => 'PROFILE_INCOMPLETE',
                    'message' => 'Profil belum lengkap. Lengkapi data sebelum melanjutkan pembayaran.',
                    'missing_fields' => $missing,
                    // Frontend dapat gunakan ini untuk mengarahkan ke halaman edit profil
                    'redirect_to' => '/profile/edit',
                    'profile_id' => $user->id,
                ], 422);
            }
        }

        // Hitung total harga barang
        $totalPrice = 0;
        foreach ($request->items as $item) {
            $product = Product::findOrFail($item['id']);

            $unitPrice = null;
            if (!empty($item['product_variant_id'])) {
                $variant = ProductVariant::where('id', $item['product_variant_id'])
                    ->where('product_id', $product->id)
                    ->first();

                if ($variant) {
                    $unitPrice = $variant->price ?? $product->price;
                }
            }

            if ($unitPrice === null) {
                // Fallback ke harga produk jika varian tidak dipilih atau tidak ditemukan
                $unitPrice = $product->price;
            }

            $totalPrice += ($unitPrice * $item['quantity']);
        }

        // Ambil data untuk perhitungan ongkir
        $origin = $request->origin; // ID kota asal
        $destination = $request->destination; // ID kota tujuan
        $weight = $request->weight; // Berat dalam gram
        $courier = $request->courier; // Kurir (jne, tiki, etc.)

        // Cek ongkir dengan RajaOngkir
        $ongkir = $this->rajaOngkirService->checkOngkir($origin, $destination, $weight, $courier);

        // Validasi ongkir (format baru: meta + data[])
        if (!isset($ongkir->data) || empty($ongkir->data)) {
            return response()->json(['error' => 'Ongkir tidak ditemukan.'], 400);
        }
        $shippingCost = (int) ($ongkir->data[0]['value'] ?? $ongkir->data[0]['value'] ?? 0);

        // Data order dan detail transaksi
        $orderDetails = [
            'order_id' => 'ORDER-' . time(),
            'gross_amount' => $totalPrice + $shippingCost, // Total pembayaran dari items + ongkir
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'shipping_address' => $request->shipping_address,
            'courier' => $courier,
            'shipping_cost' => $shippingCost,
        ];

        // Buat transaksi Midtrans dengan menggunakan MidtransService
        try {
            $transaction = $this->midtransService->createTransaction($orderDetails);
            // dd($transaction); // Debug output
            dd($orderDetails); // Debug output
            // return json_decode(json_encode($transaction));
            //yang betul
            // return response()->json([
            //     // 'payment_url' => $transaction->redirect_url
            //     'snap_token' => $transaction->token
            // ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Callback untuk verifikasi pembayaran
     */
    public function paymentCallback(Request $request)
    {
        // Tangkap data callback dari Midtrans
        $callbackData = $request->all();

        // Proses status pembayaran
        try {
            $this->midtransService->handleCallback($callbackData);
            return response()->json(['status' => 'success', 'data' => $callbackData]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'failed', 'error' => $e->getMessage()]);
        }
    }

    /**
     * Get provinces list from RajaOngkir
     */
    public function getProvinces()
    {
        try {
            $provinces = $this->rajaOngkirService->getProvinces();
            // Mengikuti format dokumentasi baru: langsung kembalikan meta + data
            return response()->json($provinces);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get cities list from RajaOngkir
     */
    public function getCities(Request $request)
    {
        try {
            // Get cities for specific province if province_id is provided
            $provinceId = $request->query('province_id');
            $cities = $this->rajaOngkirService->getCities($provinceId);
            // Ikuti format baru: meta + data
            return response()->json($cities);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function refundOrder(Request $request, $orderId)
    {
        $amount = $request->amount; // Jumlah yang akan di-refund

        // Validasi jumlah refund (misalnya tidak boleh lebih dari total pembayaran)
        $order = Order::find($orderId);
        if (!$order) {
            return response()->json(['error' => 'Order not found.'], 404);
        }

        if ($amount > $order->total_amount) {
            return response()->json(['error' => 'Refund amount exceeds the total order amount.'], 400);
        }

        // Melakukan refund melalui service Midtrans
        $refundResponse = $this->midtransService->refundTransaction($order->transaction_id, $amount);

        if (isset($refundResponse['error'])) {
            return response()->json(['error' => $refundResponse['error']], 500);
        }

        return response()->json(['status' => 'success', 'refund_response' => $refundResponse]);
    }
}
