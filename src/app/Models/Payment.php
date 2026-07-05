<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'payment_gateway_id',
        'payment_number',
        'provider',
        'payment_method',
        'status',
        'amount',
        'snap_token',
        'redirect_url',
        'transaction_id',
        'fraud_status',
        'raw_response',
        'paid_at',
        'expired_at',
    ];

    protected $casts = [
        'amount' => 'integer',
        'raw_response' => 'array',
        'paid_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function paymentGateway(): BelongsTo
    {
        return $this->belongsTo(PaymentGateway::class);
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    public function scopeSuccess(Builder $query): Builder
    {
        return $query->whereIn('status', ['settlement', 'capture']);
    }
}