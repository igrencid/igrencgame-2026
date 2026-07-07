<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Voucher extends Model
{
    protected $fillable = [
        'code',
        'name',
        'type',
        'value',
        'max_discount',
        'min_order_amount',
        'usage_limit',
        'used_count',
        'per_customer_limit',
        'starts_at',
        'ends_at',
        'is_active',
    ];

    protected $casts = [
        'value' => 'integer',
        'max_discount' => 'integer',
        'min_order_amount' => 'integer',
        'usage_limit' => 'integer',
        'used_count' => 'integer',
        'per_customer_limit' => 'integer',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    protected function code(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => Str::upper(trim((string) $value)),
        );
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function isUsableNow(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if ($this->starts_at && $this->starts_at->isFuture()) {
            return false;
        }

        if ($this->ends_at && $this->ends_at->isPast()) {
            return false;
        }

        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    public function calculateDiscount(int $amount): int
    {
        if ($amount <= 0) {
            return 0;
        }

        if ($this->type === 'percentage') {
            $discount = (int) floor($amount * ($this->value / 100));

            if ($this->max_discount) {
                $discount = min($discount, $this->max_discount);
            }

            return min($discount, $amount);
        }

        return min($this->value, $amount);
    }
}
