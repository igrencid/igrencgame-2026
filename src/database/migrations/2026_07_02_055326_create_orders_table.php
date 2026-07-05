<?php

use App\Models\Game;
use App\Models\GameProduct;
use App\Models\PaymentGateway;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->string('invoice_number')->unique();

            $table->foreignIdFor(Game::class)
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignIdFor(GameProduct::class)
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignIdFor(PaymentGateway::class)
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('game_name');
            $table->string('product_name');

            $table->json('customer_inputs')->nullable();

            $table->string('customer_name')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('customer_phone')->nullable();

            $table->unsignedBigInteger('product_price')->default(0);
            $table->unsignedBigInteger('admin_fee')->default(0);
            $table->unsignedBigInteger('total_amount')->default(0);

            $table->string('status')->default('pending');

            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expired_at')->nullable();

            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index(['customer_email', 'customer_phone']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};