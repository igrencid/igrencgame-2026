<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MidtransNotification extends Model
{
    protected $fillable = [
        'order_id',
        'payment_id',
        'provider',
        'order_id_from_provider',
        'transaction_id',
        'transaction_status',
        'payment_type',
        'fraud_status',
        'status_code',
        'signature_key',
        'gross_amount',
        'headers',
        'payload',
        'is_processed',
        'processed_at',
        'processing_error',
    ];

    protected $casts = [
        'gross_amount' => 'integer',
        'headers' => 'array',
        'payload' => 'array',
        'is_processed' => 'boolean',
        'processed_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }
}