<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('customer_service_whatsapp')->nullable()->after('seo_description');
            $table->string('customer_service_email')->nullable()->after('customer_service_whatsapp');
            $table->string('customer_service_label')->default('Hubungi CS')->after('customer_service_email');
            $table->string('customer_service_working_hours')->nullable()->after('customer_service_label');
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'customer_service_whatsapp',
                'customer_service_email',
                'customer_service_label',
                'customer_service_working_hours',
            ]);
        });
    }
};
