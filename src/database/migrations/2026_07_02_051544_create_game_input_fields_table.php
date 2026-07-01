<?php

use App\Models\Game;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('game_input_fields', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Game::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->string('label');
            $table->string('name');
            $table->string('type')->default('text');

            $table->string('placeholder')->nullable();
            $table->string('helper_text')->nullable();

            $table->boolean('is_required')->default(true);
            $table->boolean('is_active')->default(true);

            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();

            $table->index(['game_id', 'is_active', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_input_fields');
    }
};