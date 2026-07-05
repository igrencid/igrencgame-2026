<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

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

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function isMidtrans(): bool
    {
        return $this->provider === 'midtrans';
    }

    public function isProduction(): bool
    {
        return $this->mode === 'production';
    }
}