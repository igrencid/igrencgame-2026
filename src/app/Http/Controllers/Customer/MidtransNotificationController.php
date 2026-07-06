<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\Orders\OrderInvoiceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MidtransNotificationController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $orderId = (string) $request->input('order_id');
        $statusCode = (string) $request->input('status_code');
        $grossAmount = (string) $request->input('gross_amount');
        $signatureKey = (string) $request->input('signature_key');

        $serverKey = (string) config('midtrans.server_key');

        $validSignature = hash(
            'sha512',
            $orderId . $statusCode . $grossAmount . $serverKey
        );

        if (! hash_equals($validSignature, $signatureKey)) {
            return response()->json([
                'message' => 'Invalid signature',
            ], 403);
        }

        $order = Order::query()
            ->where('invoice_number', $orderId)
            ->with(['payment', 'paymentGateway'])
            ->first();

        if (! $order) {
            return response()->json([
                'message' => 'Order not found',
            ], 404);
        }

        $transactionStatus = $request->input('transaction_status');
        $fraudStatus = $request->input('fraud_status');

        $orderStatus = match (true) {
            in_array($transactionStatus, ['capture', 'settlement'], true)
                && in_array($fraudStatus, [null, 'accept'], true) => 'paid',

            $transactionStatus === 'pending' => 'pending',

            in_array($transactionStatus, ['deny', 'cancel', 'failure'], true) => 'failed',

            $transactionStatus === 'expire' => 'expired',

            default => $order->status,
        };

        $paymentStatus = match ($orderStatus) {
            'paid' => 'paid',
            'failed' => 'failed',
            'expired' => 'expired',
            default => $order->payment?->status ?? 'pending',
        };

        $order->update([
            'status' => $orderStatus,
            'paid_at' => $orderStatus === 'paid'
                ? ($order->paid_at ?: now())
                : $order->paid_at,
        ]);

        if ($order->payment) {
            $order->payment->update([
                'status' => $paymentStatus,
                'payment_method' => $request->input('payment_type'),
                'transaction_id' => $request->input('transaction_id'),
                'fraud_status' => $fraudStatus,
                'raw_response' => $request->all(),
                'paid_at' => $orderStatus === 'paid'
                    ? ($order->payment->paid_at ?: now())
                    : $order->payment->paid_at,
            ]);
        }

        app(OrderInvoiceService::class)->sendPaidInvoiceIfNeeded($order);

        return response()->json([
            'message' => 'Notification processed',
        ]);
    }
}
