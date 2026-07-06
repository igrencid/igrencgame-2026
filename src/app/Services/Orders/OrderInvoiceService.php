<?php

namespace App\Services\Orders;

use App\Mail\OrderPaidInvoiceMail;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;
use Throwable;

class OrderInvoiceService
{
    public function sendPaidInvoiceIfNeeded(Order $order): void
    {
        $order->refresh()->loadMissing([
            'payment',
            'paymentGateway',
        ]);

        if ($order->status !== 'paid') {
            return;
        }

        if ($order->invoice_email_sent_at !== null) {
            return;
        }

        if (! filled($order->customer_email)) {
            return;
        }

        try {
            Mail::to($order->customer_email)
                ->send(new OrderPaidInvoiceMail($order));

            $order->forceFill([
                'invoice_email_sent_at' => now(),
            ])->save();
        } catch (Throwable $e) {
            report($e);
        }
    }
}
