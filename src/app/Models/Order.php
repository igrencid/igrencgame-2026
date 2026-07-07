<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $fillable = [
        'customer_id',
        'api_provider_id',
        'invoice_number',
        'game_id',
        'game_product_id',
        'payment_gateway_id',
        'voucher_id',
        'voucher_code',
        'discount_amount',
        'game_name',
        'product_name',
        'customer_inputs',
        'customer_name',
        'customer_email',
        'customer_phone',
        'product_price',
        'admin_fee',
        'total_amount',
        'status',
        'paid_at',
        'invoice_email_sent_at',
        'expired_at',
    ];

    protected $casts = [
        'customer_inputs' => 'array',
        'product_price' => 'integer',
        'admin_fee' => 'integer',
        'discount_amount' => 'integer',
        'total_amount' => 'integer',
        'paid_at' => 'datetime',
        'invoice_email_sent_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    public function apiProvider(): BelongsTo
    {
        return $this->belongsTo(ApiProvider::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(GameProduct::class, 'game_product_id');
    }

    public function voucher(): BelongsTo
    {
        return $this->belongsTo(Voucher::class);
    }

    public function paymentGateway(): BelongsTo
    {
        return $this->belongsTo(PaymentGateway::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    public function scopePaid(Builder $query): Builder
    {
        return $query->where('status', 'paid');
    }
}
