<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FaqItem extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'question',
        'answer',
        'sort_order',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get the active FAQ items ordered by sort order.
     */
    public static function active()
    {
        return static::where('is_active', true)
            ->orderBy('sort_order', 'asc');
    }

    /**
     * Scope to search in question and answer.
     */
    public function scopeSearch($query, $search)
    {
        if (empty($search)) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->whereRaw('LOWER(question) LIKE ?', ['%' . strtolower($search) . '%'])
                ->orWhereRaw('LOWER(answer) LIKE ?', ['%' . strtolower($search) . '%']);
        });
    }
}
