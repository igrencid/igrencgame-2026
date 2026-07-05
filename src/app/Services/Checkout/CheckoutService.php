<?php

namespace App\Services\Checkout;

use App\Models\GameProduct;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentGateway;
use App\Services\Generators\OrderNumberGenerator;
use App\Services\Generators\PaymentNumberGenerator;
use App\Services\Payments\MidtransService;
use Illuminate\Support\Facades\DB;
use Throwable;

class CheckoutService
{
    public function __construct(
        protected OrderNumberGenerator $orderNumberGenerator,
        protected PaymentNumberGenerator $paymentNumberGenerator,
        protected MidtransService $midtransService,
    ) {
    }

    public function create(array $data): array
    {
        $product = GameProduct::query()
            ->with('game')
            ->whereKey($data['game_product_id'])
            ->where('is_active', true)
            ->firstOrFail();

        abort_if(! $product->game?->is_active, 404, 'Game tidak aktif.');

        $paymentGateway = PaymentGateway::query()
            ->whereKey($data['payment_gateway_id'])
            ->where('is_active', true)
            ->firstOrFail();

        $productPrice = (int) $product->selling_price;
        $adminFee = $this->calculateAdminFee($paymentGateway, $productPrice);
        $totalAmount = $productPrice + $adminFee;

        [$order, $payment] = DB::transaction(function () use (
            $data,
            $product,
            $paymentGateway,
            $productPrice,
            $adminFee,
            $totalAmount
        ) {
            $order = Order::query()->create([
                'invoice_number' => $this->orderNumberGenerator->generate(),

                'game_id' => $product->game_id,
                'game_product_id' => $product->id,
                'payment_gateway_id' => $paymentGateway->id,

                'game_name' => $product->game->name,
                'product_name' => $product->name,
                'customer_inputs' => $data['customer_inputs'] ?? [],

                'customer_name' => $data['customer_name'] ?? null,
                'customer_email' => $data['customer_email'] ?? null,
                'customer_phone' => $data['customer_phone'] ?? null,

                'product_price' => $productPrice,
                'admin_fee' => $adminFee,
                'total_amount' => $totalAmount,

                'status' => 'pending',
                'expired_at' => now()->addMinutes(15),
            ]);

            $payment = Payment::query()->create([
                'order_id' => $order->id,
                'payment_gateway_id' => $paymentGateway->id,

                'payment_number' => $this->paymentNumberGenerator->generate(),
                'provider' => $paymentGateway->provider,
                'payment_method' => 'midtrans_snap',

                'status' => 'pending',
                'amount' => $totalAmount,

                'expired_at' => $order->expired_at,
            ]);

            return [$order->fresh(), $payment->fresh()];
        });

        try {
            $snap = $this->midtransService->createSnapTransaction($order);

            $payment->update([
                'snap_token' => $snap->token ?? null,
                'redirect_url' => $snap->redirect_url ?? null,
                'raw_response' => [
                    'token' => $snap->token ?? null,
                    'redirect_url' => $snap->redirect_url ?? null,
                ],
            ]);
        } catch (Throwable $exception) {
            $order->update([
                'status' => 'failed',
            ]);

            $payment->update([
                'status' => 'failure',
                'raw_response' => [
                    'error' => $exception->getMessage(),
                ],
            ]);

            throw $exception;
        }

        return [
            'order' => $order->fresh(),
            'payment' => $payment->fresh(),
        ];
    }

    protected function calculateAdminFee(PaymentGateway $paymentGateway, int $amount): int
    {
        if ($paymentGateway->fee_type === 'percentage') {
            return (int) ceil($amount * ((int) $paymentGateway->fee_value / 100));
        }

        return (int) $paymentGateway->fee_value;
    }
}
