<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('provider')->default('midtrans');
            $table->string('mode')->default('sandbox');

            $table->string('fee_type')->default('fixed');
            $table->unsignedBigInteger('fee_value')->default(0);

            $table->unsignedBigInteger('minimum_amount')->default(0);
            $table->unsignedBigInteger('maximum_amount')->nullable();

            $table->string('display_label')->nullable();
            $table->text('instruction')->nullable();

            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();

            $table->index(['provider', 'mode', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_gateways');
    }
};