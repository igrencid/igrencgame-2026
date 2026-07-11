<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table): void {
            $table->json('hero_images')
                ->nullable()
                ->after('favicon_path');
        });

        /*
         * Pindahkan gambar hero lama ke hero_images agar tidak hilang.
         */
        if (Schema::hasColumn('site_settings', 'hero_image_path')) {
            DB::table('site_settings')
                ->whereNotNull('hero_image_path')
                ->where('hero_image_path', '!=', '')
                ->orderBy('id')
                ->eachById(function (object $setting): void {
                    DB::table('site_settings')
                        ->where('id', $setting->id)
                        ->update([
                            'hero_images' => json_encode([
                                $setting->hero_image_path,
                            ]),
                        ]);
                });
        }
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table): void {
            $table->dropColumn('hero_images');
        });
    }
};
