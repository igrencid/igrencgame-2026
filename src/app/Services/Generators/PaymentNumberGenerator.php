<?php

namespace App\Services\Generators;

use App\Models\Payment;

class PaymentNumberGenerator
{
    public function generate(): string
    {
        $date = now()->format('Ymd');
        $prefix = "PAY-{$date}-";

        $latestNumber = Payment::query()
            ->where('payment_number', 'like', $prefix . '%')
            ->orderByDesc('id')
            ->value('payment_number');

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
