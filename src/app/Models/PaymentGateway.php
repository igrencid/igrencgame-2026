<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentGateway extends Model
{
    protected $fillable = [
        'name',
        'provider',
        'mode',
        'fee_type',
        'fee_value',
        'minimum_amount',
        'maximum_amount',
        'display_label',
        'instruction',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'fee_value' => 'integer',
        'minimum_amount' => 'integer',
        'maximum_amount' => 'integer',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public static function ensureMidtrans(): self
    {
        $mode = config('midtrans.is_production') ? 'production' : 'sandbox';

        return self::query()->updateOrCreate(
            [
                'provider' => 'midtrans',
                'mode' => $mode,
            ],
            [
                'name' => $mode === 'production'
                    ? 'Midtrans Production'
                    : 'Midtrans Sandbox',
                'display_label' => 'Midtrans',
                'fee_type' => 'fixed',
                'fee_value' => 2500,
                'minimum_amount' => 0,
                'maximum_amount' => null,
                'instruction' => 'Bayar melalui Midtrans Snap.',
                'is_active' => true,
                'sort_order' => 1,
            ]
        );
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
