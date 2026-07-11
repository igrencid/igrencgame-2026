<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('promos', function (Blueprint $table) {

            $table->id();

            $table->string('judul');

            $table->string('slug')
                ->unique();

            $table->text('deskripsi');

            $table->string('gambar')
                ->nullable();

            $table->string('kode_promo')
                ->nullable();

            $table->integer('diskon')
                ->default(0);

            $table->date('tanggal_mulai');

            $table->date('tanggal_akhir');

            $table->boolean('status')
                ->default(true);

            $table->timestamps();

        });
    }


    public function down(): void
    {
        Schema::dropIfExists('promos');
    }

};