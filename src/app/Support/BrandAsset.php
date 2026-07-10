<?php

namespace App\Support;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class BrandAsset
{
    public static function logoUrl(): ?string
    {
        try {
            if (! class_exists(SiteSetting::class)) {
                return null;
            }

            $model = new SiteSetting();
            $table = $model->getTable();

            if (! Schema::hasTable($table)) {
                return null;
            }

            $setting = SiteSetting::query()->latest('id')->first();

            if (! $setting) {
                return null;
            }

            $logoColumns = [
                'logo_path',
                'site_logo',
                'logo',
                'logo_url',
                'brand_logo',
                'brand_logo_path',
                'header_logo',
                'app_logo',
            ];

            foreach ($logoColumns as $column) {
                if (! Schema::hasColumn($table, $column)) {
                    continue;
                }

                $value = $setting->{$column};

                if (filled($value)) {
                    return self::normalizeUrl((string) $value);
                }
            }

            return null;
        } catch (Throwable) {
            return null;
        }
    }

    public static function brandName(): string
    {
        return config('brand.name', config('app.name', 'Igrenc'));
    }

    private static function normalizeUrl(string $path): string
    {
        $path = trim($path);

        if (Str::startsWith($path, ['http://', 'https://', '//'])) {
            return $path;
        }

        if (Str::startsWith($path, ['/storage/', 'storage/'])) {
            return asset(ltrim($path, '/'));
        }

        if (Str::startsWith($path, ['/'])) {
            return url($path);
        }

        return Storage::disk('public')->url($path);
    }
}
