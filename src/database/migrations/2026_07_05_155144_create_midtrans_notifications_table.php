<?php

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('midtrans_notifications', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Order::class)
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignIdFor(Payment::class)
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('provider')->default('midtrans');

            $table->string('order_id_from_provider')->nullable();
            $table->string('transaction_id')->nullable();

            $table->string('transaction_status')->nullable();
            $table->string('payment_type')->nullable();
            $table->string('fraud_status')->nullable();

            $table->string('status_code')->nullable();
            $table->string('signature_key')->nullable();

            $table->unsignedBigInteger('gross_amount')->default(0);

            $table->json('headers')->nullable();
            $table->json('payload')->nullable();

            $table->boolean('is_processed')->default(false);
            $table->timestamp('processed_at')->nullable();
            $table->text('processing_error')->nullable();

            $table->timestamps();

            $table->index(['provider', 'transaction_status']);
            $table->index('transaction_id');
            $table->index('order_id_from_provider');
            $table->index('is_processed');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('midtrans_notifications');
    }
};