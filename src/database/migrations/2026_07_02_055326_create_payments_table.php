<?php

use App\Models\Order;
use App\Models\PaymentGateway;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Order::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignIdFor(PaymentGateway::class)
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('payment_number')->unique();

            $table->string('provider')->default('midtrans');
            $table->string('payment_method')->nullable();

            $table->string('status')->default('pending');

            $table->unsignedBigInteger('amount')->default(0);

            $table->string('snap_token')->nullable();
            $table->text('redirect_url')->nullable();

            $table->string('transaction_id')->nullable();
            $table->string('fraud_status')->nullable();

            $table->json('raw_response')->nullable();

            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expired_at')->nullable();

            $table->timestamps();

            $table->index(['provider', 'status']);
            $table->index('transaction_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};