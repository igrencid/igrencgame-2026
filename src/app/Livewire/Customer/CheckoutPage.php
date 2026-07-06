<?php

namespace App\Livewire\Customer;

use App\Models\Game;
use App\Models\GameProduct;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentGateway;
use App\Services\Generators\OrderNumberGenerator;
use App\Services\Generators\PaymentNumberGenerator;
use App\Services\Payments\MidtransService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Throwable;

class CheckoutPage extends Component
{
    private const ORDER_EXPIRY_MINUTES = 10;

    public Game $game;

    public ?int $productId = null;

    public ?int $paymentGatewayId = null;

    public array $customerInputs = [];

    public string $customerName = '';

    public string $customerEmail = '';

    public string $customerPhone = '';

    public bool $isSubmitting = false;

    public function mount(string $slug): void
    {
        $this->game = Game::query()
            ->active()
            ->where('slug', $slug)
            ->with([
                'products' => fn ($query) => $query
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->orderBy('selling_price'),

                'inputFields' => fn ($query) => $query
                    ->where('is_active', true)
                    ->orderBy('sort_order'),
            ])
            ->firstOrFail();

        $queryProductId = request()->integer('product');

        $this->productId = $this->game->products->contains('id', $queryProductId)
            ? $queryProductId
            : $this->game->products->first()?->id;

        $gateway = PaymentGateway::ensureMidtrans();

        $this->paymentGatewayId = $gateway->id;

        foreach ($this->game->inputFields as $field) {
            $this->customerInputs[(string) $field->id] = '';
        }
    }

    public function selectProduct(int $productId): void
    {
        if (! $this->game->products->contains('id', $productId)) {
            return;
        }

        $this->productId = $productId;
    }

    public function selectPaymentGateway(int $gatewayId): void
    {
        $gateway = PaymentGateway::ensureMidtrans();

        if ($gateway->id !== $gatewayId) {
            return;
        }

        $this->paymentGatewayId = $gateway->id;
    }

    public function placeOrder()
    {
        if ($this->isSubmitting) {
            return null;
        }

        $gateway = PaymentGateway::ensureMidtrans();

        $this->paymentGatewayId = $gateway->id;

        $rules = [
            'productId' => ['required', 'integer'],
            'paymentGatewayId' => ['required', 'integer'],
            'customerName' => ['required', 'string', 'max:100'],
            'customerEmail' => ['required', 'email', 'max:150'],
            'customerPhone' => ['required', 'string', 'max:30'],
        ];

        $attributes = [
            'productId' => 'produk',
            'paymentGatewayId' => 'metode pembayaran',
            'customerName' => 'nama pelanggan',
            'customerEmail' => 'email pelanggan',
            'customerPhone' => 'nomor WhatsApp',
        ];

        foreach ($this->game->inputFields as $field) {
            $rules['customerInputs.' . $field->id] = [
                $field->is_required ? 'required' : 'nullable',
                'string',
                'max:150',
            ];

            $attributes['customerInputs.' . $field->id] = $field->label;
        }

        $this->validate($rules, [], $attributes);

        $lockKey = 'checkout-submit:' . session()->getId();

        if (! Cache::add($lockKey, true, now()->addSeconds(45))) {
            $this->addError('checkout', 'Pesanan sedang diproses. Jangan klik tombol berulang.');

            return null;
        }

        $this->isSubmitting = true;
        $releaseLock = true;
        $order = null;

        try {
            $product = GameProduct::query()
                ->whereKey($this->productId)
                ->where('game_id', $this->game->id)
                ->where('is_active', true)
                ->firstOrFail();

            $productPrice = (int) $product->selling_price;
            $adminFee = $this->calculateAdminFee($gateway, $productPrice);
            $totalAmount = $productPrice + $adminFee;
            $expiredAt = now()->addMinutes(self::ORDER_EXPIRY_MINUTES);

            $order = DB::transaction(function () use ($product, $gateway, $productPrice, $adminFee, $totalAmount, $expiredAt) {
                $order = Order::query()->create([
                    'invoice_number' => app(OrderNumberGenerator::class)->generate(),
                    'game_id' => $this->game->id,
                    'game_product_id' => $product->id,
                    'payment_gateway_id' => $gateway->id,
                    'game_name' => $this->game->name,
                    'product_name' => $product->name,
                    'customer_inputs' => $this->formatCustomerInputs(),
                    'customer_name' => $this->customerName,
                    'customer_email' => $this->customerEmail,
                    'customer_phone' => $this->customerPhone,
                    'product_price' => $productPrice,
                    'admin_fee' => $adminFee,
                    'total_amount' => $totalAmount,
                    'status' => 'pending',
                    'expired_at' => $expiredAt,
                ]);

                Payment::query()->create([
                    'order_id' => $order->id,
                    'payment_gateway_id' => $gateway->id,
                    'payment_number' => app(PaymentNumberGenerator::class)->generate(),
                    'provider' => $gateway->provider,
                    'status' => 'pending',
                    'amount' => $totalAmount,
                    'expired_at' => $expiredAt,
                ]);

                return $order;
            });

            $payment = $order->payment()->firstOrFail();

            $snap = app(MidtransService::class)->createSnapTransaction($order);

            $payment->update([
                'snap_token' => $snap->token ?? null,
                'redirect_url' => $snap->redirect_url ?? null,
                'raw_response' => json_decode(json_encode($snap), true),
            ]);

            $releaseLock = false;

            return redirect()->route('payment.show', [
                'invoice' => $order->invoice_number,
            ]);
        } catch (Throwable $e) {
            report($e);

            if ($order) {
                $order->update([
                    'status' => 'failed',
                ]);

                $order->payment?->update([
                    'status' => 'failed',
                ]);
            }

            $this->addError('checkout', 'Gagal membuat pesanan. Coba lagi beberapa saat.');
        } finally {
            $this->isSubmitting = false;

            if ($releaseLock) {
                Cache::forget($lockKey);
            }
        }

        return null;
    }

    private function calculateAdminFee(PaymentGateway $gateway, int $productPrice): int
    {
        if ($gateway->fee_type === 'percentage') {
            return (int) ceil($productPrice * ((int) $gateway->fee_value / 100));
        }

        return (int) $gateway->fee_value;
    }

    private function formatCustomerInputs(): array
    {
        return $this->game->inputFields
            ->mapWithKeys(function ($field) {
                return [
                    $field->label => $this->customerInputs[(string) $field->id] ?? null,
                ];
            })
            ->toArray();
    }

    public function render(): View
    {
        $gateway = PaymentGateway::ensureMidtrans();

        $products = $this->game->products;

        $paymentGateways = collect([$gateway]);

        $selectedProduct = $products->firstWhere('id', $this->productId);

        $selectedGateway = $gateway;

        $productPrice = (int) ($selectedProduct?->selling_price ?? 0);

        $adminFee = $this->calculateAdminFee($gateway, $productPrice);

        return view('livewire.customer.checkout-page', [
            'game' => $this->game,
            'products' => $products,
            'paymentGateways' => $paymentGateways,
            'selectedProduct' => $selectedProduct,
            'selectedGateway' => $selectedGateway,
            'productPrice' => $productPrice,
            'adminFee' => $adminFee,
            'totalAmount' => $productPrice + $adminFee,
        ])->layout('layouts.public', [
            'title' => 'Checkout ' . $this->game->name,
        ]);
    }
}
