<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('orders', 'voucher_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->foreignId('voucher_id')
                    ->nullable()
                    ->after('payment_gateway_id')
                    ->constrained('vouchers')
                    ->nullOnDelete();

                $table->string('voucher_code')->nullable()->after('voucher_id');
                $table->unsignedInteger('discount_amount')->default(0)->after('voucher_code');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('orders', 'voucher_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropConstrainedForeignId('voucher_id');
            });
        }

        if (Schema::hasColumn('orders', 'voucher_code')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('voucher_code');
            });
        }

        if (Schema::hasColumn('orders', 'discount_amount')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('discount_amount');
            });
        }
    }
};
