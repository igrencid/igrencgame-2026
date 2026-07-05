<?php

namespace App\Services\Generators;

use App\Models\Order;

class OrderNumberGenerator
{
    public function generate(): string
    {
        $date = now()->format('Ymd');
        $prefix = "INV-{$date}-";

        $latestNumber = Order::query()
            ->where('invoice_number', 'like', $prefix . '%')
            ->orderByDesc('id')
            ->value('invoice_number');

        $nextSequence = 1;

        if ($latestNumber) {
            preg_match('/(\d{6})$/', $latestNumber, $matches);

            if (isset($matches[1])) {
                $nextSequence = ((int) $matches[1]) + 1;
            }
        }

        return $prefix . str_pad((string) $nextSequence, 6, '0', STR_PAD_LEFT);
    }
}
