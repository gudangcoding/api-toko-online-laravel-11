<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Services\MidtransService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    /**
     * Get pending payments for user
     */
    public function getPendingPayments(Request $request)
    {
        $payments = Payment::with(['order'])
            ->whereIn('status', ['pending', 'draft'])
            ->where('expired_at', '>', Carbon::now())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $payments
        ]);
    }

    /**
     * Resume pending payment
     */
    public function resumePayment(Request $request, string $orderId)
    {
        // Find the order and payment
        $order = Order::with('payment')->findOrFail($orderId);
        $payment = $order->payment;

        // Check if payment exists and is still pending/draft
        if (!$payment || !in_array($payment->status, ['pending', 'draft'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'No pending payment found for this order'
            ], 404);
        }

        // Check if payment has expired
        if (Carbon::parse($payment->expired_at)->isPast()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Payment has expired'
            ], 400);
        }

        try {
            // Get current transaction status from Midtrans
            $transactionStatus = $this->midtransService->getTransactionStatus($order->order_id);
            
            // If transaction is still active, return the payment URL
            if (in_array($transactionStatus->transaction_status, ['pending', 'draft'])) {
                return response()->json([
                    'status' => 'success',
                    'data' => [
                        'snap_token' => $payment->snap_token,
                        'payment_url' => $payment->payment_url,
                        'expired_at' => $payment->expired_at,
                    ]
                ]);
            }

            // If old transaction is expired or cancelled, create new transaction
            $orderDetails = [
                'order_id' => $order->order_id,
                'gross_amount' => $order->total_amount,
                'customer_name' => $order->customer_name,
                'customer_email' => $order->customer_email,
                'customer_phone' => $order->customer_phone,
            ];

            $transaction = $this->midtransService->createTransaction($orderDetails);

            // Update payment details
            $payment->update([
                'snap_token' => $transaction->token,
                'payment_url' => $transaction->redirect_url,
                'expired_at' => Carbon::now()->addDays(1),
                'status' => 'pending'
            ]);

            return response()->json([
                'status' => 'success',
                'data' => [
                    'snap_token' => $transaction->token,
                    'payment_url' => $transaction->redirect_url,
                    'expired_at' => $payment->expired_at,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check payment status
     */
    public function checkStatus(string $orderId)
    {
        try {
            $order = Order::with('payment')->findOrFail($orderId);
            $status = $this->midtransService->getTransactionStatus($order->order_id);

            return response()->json([
                'status' => 'success',
                'data' => [
                    'order_id' => $order->order_id,
                    'transaction_status' => $status->transaction_status,
                    'payment_type' => $status->payment_type ?? null,
                    'transaction_time' => $status->transaction_time ?? null,
                    'expired_at' => $order->payment->expired_at,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create IRIS disbursement (payout)
     * Expected input: bank, account, name, amount, notes (optional), order_id (optional)
     */
    public function createDisbursement(Request $request)
    {
        $request->validate([
            'bank' => 'required|string',
            'account' => 'required|string',
            'name' => 'required|string',
            'amount' => 'required|numeric|min:1',
            'notes' => 'nullable|string',
            'order_id' => 'nullable|string',
        ]);

        $reference = $request->order_id ?? 'DISB-' . time();

        // Build params in a generic shape expected by the MidtransService IRIS wrapper
        $params = [
            'beneficiaries' => [
                [
                    'bank' => strtoupper($request->bank),
                    'account' => $request->account,
                    'name' => $request->name,
                    'amount' => (int) $request->amount,
                    'remark' => $request->notes ?? '',
                    'reference' => $reference,
                ]
            ]
        ];

        try {
            $result = $this->midtransService->createDisbursement($params);

            return response()->json([
                'status' => 'success',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Approve IRIS disbursement (some IRIS flows require approval)
     * Expected input: reference or disbursement id
     */
    public function approveDisbursement(Request $request)
    {
        $request->validate([
            'reference' => 'nullable|string',
            'disbursement_id' => 'nullable|string',
        ]);

        $reference = $request->reference ?? $request->disbursement_id;
        if (!$reference) {
            return response()->json(['status' => 'error', 'message' => 'reference or disbursement_id is required'], 422);
        }

        try {
            $params = ['reference' => $reference];
            $result = $this->midtransService->approveDisbursement($params);

            return response()->json([
                'status' => 'success',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get IRIS disbursement status by reference
     */
    public function getDisbursementStatus(string $reference)
    {
        try {
            $result = $this->midtransService->getDisbursementStatus($reference);

            return response()->json([
                'status' => 'success',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get IRIS balance
     */
    public function getIrisBalance()
    {
        try {
            $balance = $this->midtransService->getBalance();
            return response()->json(['status' => 'success', 'data' => $balance]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    
}
