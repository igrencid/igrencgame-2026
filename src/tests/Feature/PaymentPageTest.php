<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

it('renders the payment page successfully', function () {
    Schema::create('site_settings', function (Blueprint $table) {
        $table->id();
        $table->string('site_name')->nullable();
        $table->string('tagline')->nullable();
        $table->text('seo_description')->nullable();
        $table->string('logo_url')->nullable();
        $table->string('favicon_url')->nullable();
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });

    $response = $this->get('/payment/test-invoice');

    $response->assertStatus(200);
});
