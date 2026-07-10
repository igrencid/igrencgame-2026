<?php

namespace App\Observers;

use App\Models\Order;
use App\Services\Orders\SendPaidInvoiceEmail;

class OrderObserver
{
    public function updated(Order $order): void
    {
        if (! $order->wasChanged('status')) {
            return;
        }

        if ($order->status !== 'paid') {
            return;
        }

        app(SendPaidInvoiceEmail::class)->send($order);
    }
}
