<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameProduct extends Model
{
    protected $fillable = [
        'game_id',
        'name',
        'code',
        'base_price',
        'selling_price',
        'description',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'base_price' => 'integer',
        'selling_price' => 'integer',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}