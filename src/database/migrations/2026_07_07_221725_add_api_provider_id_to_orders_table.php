<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('orders', 'api_provider_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->foreignId('api_provider_id')
                    ->nullable()
                    ->constrained('api_providers')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('orders', 'api_provider_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropConstrainedForeignId('api_provider_id');
            });
        }
    }
};