<?php

namespace App\Services\Orders;

use App\Mail\OrderPaidInvoiceMail;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendPaidInvoiceEmail
{
    public function send(Order $order): bool
    {
        $order->refresh();

        if ($order->status !== 'paid') {
            return false;
        }

        if (blank($order->customer_email)) {
            return false;
        }

        if ($order->invoice_email_sent_at) {
            return false;
        }

        try {
            Mail::to($order->customer_email)->send(
                new OrderPaidInvoiceMail($order)
            );

            $order->forceFill([
                'invoice_email_sent_at' => now(),
            ])->save();

            return true;
        } catch (Throwable $exception) {
            report($exception);

            return false;
        }
    }
}
