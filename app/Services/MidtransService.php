<?php

namespace App\Services;

use App\Models\Order;
use Midtrans\Config;
use Midtrans\CoreApi;
use Midtrans\Transaction;

class MidtransService
{

    public function __construct()
    {
        // Set konfigurasi Midtrans
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$clientKey = env('MIDTRANS_CLIENT_KEY');
        Config::$isProduction = env('MIDTRANS_ENVIRONMENT') === 'production';

    }

    /**
     * Membuat transaksi Midtrans menggunakan Core API
     */
    public function createTransaction($orderDetails)
    {
        // Parameter transaksi Midtrans
        $params = [
            'payment_type' => $orderDetails['payment_type'] ?? 'bank_transfer',
            'transaction_details' => [
                'order_id' => $orderDetails['order_id'],
                'gross_amount' => $orderDetails['gross_amount'],
            ],
            'customer_details' => [
                'first_name' => $orderDetails['customer_name'],
                'email' => $orderDetails['customer_email'],
                'phone' => $orderDetails['customer_phone'],
            ],
            'shipping_address' => $orderDetails['shipping_address'],
        ];

        // Add bank transfer details if payment type is bank_transfer
        if ($params['payment_type'] === 'bank_transfer') {
            $params['bank_transfer'] = [
                'bank' => $orderDetails['bank'] ?? 'bca'
            ];
        }
        // Add for credit card payment
        else if ($params['payment_type'] === 'credit_card') {
            $params['credit_card'] = [
                'token_id' => $orderDetails['card_token'],
                'authentication' => true,
            ];
        }
        // Add for e-wallet payment
        else if (in_array($params['payment_type'], ['gopay', 'shopeepay'])) {
            $params[$params['payment_type']] = [
                'enable_callback' => true,
                'callback_url' => env('APP_URL') . '/api/payments/callback'
            ];
        }

        try {
            // Membuat transaksi menggunakan Core API
            $chargeResponse = CoreApi::charge($params);
            
            return [
                'status_code' => $chargeResponse->status_code,
                'transaction_id' => $chargeResponse->transaction_id,
                'order_id' => $chargeResponse->order_id,
                'gross_amount' => $chargeResponse->gross_amount,
                'payment_type' => $chargeResponse->payment_type,
                'transaction_status' => $chargeResponse->transaction_status,
                'transaction_time' => $chargeResponse->transaction_time,
                'va_numbers' => $chargeResponse->va_numbers ?? null,
                'payment_code' => $chargeResponse->payment_code ?? null,
                'bill_key' => $chargeResponse->bill_key ?? null,
                'biller_code' => $chargeResponse->biller_code ?? null,
                'actions' => $chargeResponse->actions ?? null,
            ];
        } catch (\Exception $e) {
            throw new \Exception("Error creating Midtrans transaction: " . $e->getMessage());
        }
    }

    // Fungsi untuk memverifikasi status pembayaran
    public function handleCallback(array $callbackData)
    {
        $transactionStatus = $callbackData['transaction_status'] ?? null;
        $orderId = $callbackData['order_id'] ?? null;

        if (!$orderId || !$transactionStatus) {
            return false;
        }

        $order = Order::where('transaction_id', $orderId)->first();
        if (!$order) {
            return false;
        }

        switch ($transactionStatus) {
            case 'capture':
            case 'settlement':
                $order->update(['payment_status' => 'paid']);
                break;

            case 'pending':
                $order->update(['payment_status' => 'pending']);
                break;

            case 'deny':
            case 'cancel':
            case 'expire':
                $order->update(['payment_status' => 'failed']);
                break;
        }

        return true;
    }

    public function refundTransaction(string $orderId, int $amount)
    {
        try {
            $refundResponse = Transaction::refund($orderId, $amount);
            return $refundResponse;
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    // IRIS functions removed as requested

    /**
     * Get transaction status
     */
    public function getTransactionStatus($orderId)
    {
        try {
            return Transaction::status($orderId);
        } catch (\Exception $e) {
            throw new \Exception("Error getting transaction status: " . $e->getMessage());
        }
    }
}
