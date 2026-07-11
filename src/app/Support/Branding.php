<?php

namespace App\Support;

use App\Models\SiteSetting;
use Throwable;

final class Branding
{
    private static bool $resolved = false;

    private static ?SiteSetting $setting = null;

    public static function setting(): SiteSetting
    {
        if (self::$resolved && self::$setting) {
            return self::$setting;
        }

        self::$resolved = true;

        try {
            self::$setting = SiteSetting::current();
        } catch (Throwable) {
            self::$setting = new SiteSetting([
                'site_name' => config('app.name', 'Igrenc'),
                'tagline' => 'Fast Game Top Up',
            ]);
        }

        return self::$setting;
    }

    public static function name(): string
    {
        return self::setting()->site_name
            ?: config('app.name', 'Igrenc');
    }

    public static function tagline(): string
    {
        return self::setting()->tagline
            ?: 'Fast Game Top Up';
    }

    public static function logoPath(): ?string
    {
        return self::normalizePath(self::setting()->logo_path);
    }

    public static function logoUrl(): ?string
    {
        return self::setting()->logo_url;
    }

    public static function faviconPath(): ?string
    {
        return self::normalizePath(self::setting()->favicon_path);
    }

    public static function faviconUrl(): ?string
    {
        return self::setting()->favicon_url;
    }

    /**
     * @return array<int, string>
     */
    public static function heroImagePaths(): array
    {
        $paths = collect(self::setting()->hero_images ?? [])
            ->filter(fn (mixed $path): bool => is_string($path) && trim($path) !== '')
            ->map(fn (string $path): string => trim($path))
            ->take(5)
            ->values();

        if ($paths->isEmpty() && filled(self::setting()->hero_image_path)) {
            $paths->push(self::setting()->hero_image_path);
        }

        return $paths->all();
    }

    /**
     * @return array<int, string>
     */
    public static function heroImageUrls(): array
    {
        return self::setting()->hero_image_urls;
    }

    public static function heroImagePath(): ?string
    {
        return self::heroImagePaths()[0] ?? null;
    }

    public static function heroImageUrl(): ?string
    {
        return self::heroImageUrls()[0] ?? null;
    }

    private static function normalizePath(mixed $path): ?string
    {
        if (! is_string($path) || trim($path) === '') {
            return null;
        }

        return trim($path);
    }
}
