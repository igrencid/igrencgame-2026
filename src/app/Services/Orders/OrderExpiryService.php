<?php

namespace App\Services\Orders;

use App\Models\Order;
use Illuminate\Support\Facades\DB;

class OrderExpiryService
{
    public const EXPIRY_MINUTES = 10;

    public function expireIfNeeded(Order $order): Order
    {
        $order->refresh()->loadMissing(['payment', 'paymentGateway']);

        if ($order->status !== 'pending') {
            return $order;
        }

        if (! $order->expired_at) {
            $order->update([
                'expired_at' => ($order->created_at ?: now())->copy()->addMinutes(self::EXPIRY_MINUTES),
            ]);

            $order->refresh()->loadMissing(['payment', 'paymentGateway']);
        }

        if ($order->expired_at->isFuture()) {
            return $order;
        }

        DB::transaction(function () use ($order): void {
            $order->update([
                'status' => 'failed',
            ]);

            if ($order->payment && $order->payment->status === 'pending') {
                $order->payment->update([
                    'status' => 'failed',
                ]);
            }
        });

        return $order->fresh(['payment', 'paymentGateway']);
    }

    public function secondsRemaining(Order $order): int
    {
        if ($order->status !== 'pending' || ! $order->expired_at) {
            return 0;
        }

        return max(0, (int) now()->diffInSeconds($order->expired_at, false));
    }
}
