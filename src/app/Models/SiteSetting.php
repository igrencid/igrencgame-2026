<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SiteSetting extends Model
{
    protected $fillable = [
        'site_name',
        'tagline',
        'logo_path',
        'favicon_path',
        'seo_description',
        'is_active',
        'customer_service_whatsapp',
        'customer_service_email',
        'customer_service_label',
        'customer_service_working_hours',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('site_setting.current'));
        static::deleted(fn () => Cache::forget('site_setting.current'));
    }

    public static function current(): self
    {
        return Cache::rememberForever('site_setting.current', function () {
            return static::query()
                ->where('is_active', true)
                ->latest('id')
                ->first()
                ?? new static([
                    'site_name' => config('app.name', 'IgrencGame'),
                    'tagline' => 'Fast Game Top Up',
                    'seo_description' => 'Platform top up game online cepat, aman, dan mudah digunakan.',
                ]);
        });
    }

    public function getLogoUrlAttribute(): ?string
    {
        if (! $this->logo_path) {
            return null;
        }

        return Storage::disk('public')->url($this->logo_path);
    }

    public function getFaviconUrlAttribute(): ?string
    {
        if (! $this->favicon_path) {
            return null;
        }

        return Storage::disk('public')->url($this->favicon_path);
    }
}