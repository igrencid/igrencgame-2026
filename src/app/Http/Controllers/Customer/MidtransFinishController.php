<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\Orders\OrderInvoiceService;
use App\Services\Payments\MidtransService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Throwable;

class MidtransFinishController extends Controller
{
    public function __invoke(Request $request, MidtransService $midtrans): RedirectResponse
    {
        $invoice = (string) $request->query('order_id', '');

        if ($invoice === '') {
            return redirect()->route('home');
        }

        $order = Order::query()
            ->where('invoice_number', $invoice)
            ->with(['payment', 'paymentGateway'])
            ->first();

        if (! $order) {
            return redirect()->route('home');
        }

        try {
            $status = $midtrans->getTransactionStatus($invoice);

            $transactionStatus = $status->transaction_status ?? null;
            $fraudStatus = $status->fraud_status ?? null;

            $orderStatus = match (true) {
                in_array($transactionStatus, ['capture', 'settlement'], true)
                    && in_array($fraudStatus, [null, 'accept'], true) => 'paid',

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
                    'payment_method' => $status->payment_type ?? null,
                    'transaction_id' => $status->transaction_id ?? null,
                    'fraud_status' => $fraudStatus,
                    'raw_response' => json_decode(json_encode($status), true),
                    'paid_at' => $orderStatus === 'paid'
                        ? ($order->payment->paid_at ?: now())
                        : $order->payment->paid_at,
                ]);
            }

            app(OrderInvoiceService::class)->sendPaidInvoiceIfNeeded($order);
        } catch (Throwable $e) {
            report($e);
        }

        return redirect()->route('orders.show', [
            'invoice' => $order->invoice_number,
        ]);
    }
}
