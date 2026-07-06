<?php

namespace App\Services\Generators;

use App\Models\Order;
use Illuminate\Support\Str;

class OrderNumberGenerator
{
    public function generate(): string
    {
        do {
            $number = 'INV-' . now()->format('Ymd-His') . '-' . Str::upper(Str::random(6));
        } while (
            Order::query()->where('invoice_number', $number)->exists()
        );

        return $number;
    }
}
