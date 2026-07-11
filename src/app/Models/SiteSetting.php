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
        'hero_image_path',
        'hero_images',
        'seo_description',
        'is_active',
        'customer_service_whatsapp',
        'customer_service_email',
        'customer_service_label',
        'customer_service_working_hours',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'hero_images' => 'array',
    ];

    protected static function booted(): void
    {
        static::saved(function (): void {
            Cache::forget('site_setting.current');
        });

        static::deleted(function (): void {
            Cache::forget('site_setting.current');
        });
    }

    public static function current(): self
    {
        return Cache::rememberForever(
            'site_setting.current',
            function (): self {
                return static::query()
                    ->where('is_active', true)
                    ->latest('id')
                    ->first()
                    ?? new static([
                        'site_name' => config('app.name', 'Igrenc'),
                        'tagline' => 'Fast Game Top Up',
                        'seo_description' => 'Platform top up game online cepat, aman, dan mudah digunakan.',
                    ]);
            }
        );
    }

    public function getLogoUrlAttribute(): ?string
    {
        return $this->publicFileUrl($this->logo_path);
    }

    public function getFaviconUrlAttribute(): ?string
    {
        return $this->publicFileUrl($this->favicon_path);
    }

    /**
     * URL gambar hero pertama.
     */
    public function getHeroImageUrlAttribute(): ?string
    {
        return $this->hero_image_urls[0] ?? null;
    }

    /**
     * Daftar URL slider hero maksimal 5 gambar.
     *
     * @return array<int, string>
     */
    public function getHeroImageUrlsAttribute(): array
    {
        $paths = collect($this->hero_images ?? [])
            ->filter(
                fn (mixed $path): bool =>
                    is_string($path) && trim($path) !== ''
            )
            ->map(
                fn (string $path): string => trim($path)
            )
            ->take(5)
            ->values();

        /*
         * Fallback untuk gambar hero versi lama.
         */
        if ($paths->isEmpty() && filled($this->hero_image_path)) {
            $paths->push($this->hero_image_path);
        }

        return $paths
            ->map(
                fn (string $path): ?string =>
                    $this->publicFileUrl($path)
            )
            ->filter()
            ->values()
            ->all();
    }

    private function publicFileUrl(?string $path): ?string
    {
        if (! is_string($path) || trim($path) === '') {
            return null;
        }

        $path = trim($path);

        if (
            str_starts_with($path, 'http://')
            || str_starts_with($path, 'https://')
        ) {
            return $path;
        }

        $path = ltrim($path, '/');

        if (str_starts_with($path, 'storage/')) {
            return asset($path);
        }

        return Storage::disk('public')->url($path);
    }
}
