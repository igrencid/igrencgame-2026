<?php

namespace App\Livewire\Customer;

use App\Models\Game;
use App\Models\GameProduct;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentGateway;
use App\Models\Voucher;
use App\Services\Generators\OrderNumberGenerator;
use App\Services\Generators\PaymentNumberGenerator;
use App\Services\Payments\MidtransService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
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

    public string $voucherCode = '';

    public ?int $appliedVoucherId = null;

    public ?string $appliedVoucherCode = null;

    public int $discountAmount = 0;

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

        if ($customer = Auth::guard('customer')->user()) {
            $this->customerName = $customer->name ?? '';
            $this->customerEmail = $customer->email ?? '';
            $this->customerPhone = $customer->phone ?? '';
        }
    }

    public function selectProduct(int $productId): void
    {
        if (! $this->game->products->contains('id', $productId)) {
            return;
        }

        $this->productId = $productId;
        $this->clearVoucher();
    }

    public function selectPaymentGateway(int $gatewayId): void
    {
        $gateway = PaymentGateway::ensureMidtrans();

        if ($gateway->id !== $gatewayId) {
            return;
        }

        $this->paymentGatewayId = $gateway->id;
        $this->clearVoucher();
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
            $subtotalAmount = $productPrice + $adminFee;
            $expiredAt = now()->addMinutes(self::ORDER_EXPIRY_MINUTES);
            $customerId = Auth::guard('customer')->id();

            $order = DB::transaction(function () use ($product, $gateway, $productPrice, $adminFee, $subtotalAmount, $expiredAt, $customerId) {
                $voucher = null;
                $discountAmount = 0;

                if ($this->appliedVoucherId && $this->appliedVoucherCode && $customerId) {
                    $voucher = Voucher::query()
                        ->whereKey($this->appliedVoucherId)
                        ->lockForUpdate()
                        ->first();

                    if (! $voucher || $voucher->code !== $this->appliedVoucherCode) {
                        throw \Illuminate\Validation\ValidationException::withMessages([
                            'voucherCode' => 'Voucher tidak valid.',
                        ]);
                    }

                    if ($error = $this->voucherError($voucher, $customerId, $subtotalAmount)) {
                        throw \Illuminate\Validation\ValidationException::withMessages([
                            'voucherCode' => $error,
                        ]);
                    }

                    $discountAmount = min(
                        $voucher->calculateDiscount($subtotalAmount),
                        max(0, $subtotalAmount - 1)
                    );

                    if ($discountAmount <= 0) {
                        throw \Illuminate\Validation\ValidationException::withMessages([
                            'voucherCode' => 'Voucher tidak menghasilkan potongan.',
                        ]);
                    }

                    $voucher->increment('used_count');
                }

                $totalAmount = max(1, $subtotalAmount - $discountAmount);

                $order = Order::query()->create([
                    'customer_id' => $customerId,
                    'invoice_number' => app(OrderNumberGenerator::class)->generate(),
                    'game_id' => $this->game->id,
                    'game_product_id' => $product->id,
                    'payment_gateway_id' => $gateway->id,
                    'voucher_id' => $voucher?->id,
                    'voucher_code' => $voucher?->code,
                    'discount_amount' => $discountAmount,
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


    public function applyVoucher(): void
    {
        $this->resetErrorBag('voucherCode');

        $customerId = Auth::guard('customer')->id();

        if (! $customerId) {
            $this->addError('voucherCode', 'Masuk dulu untuk menggunakan kode voucher.');
            return;
        }

        $this->validate([
            'voucherCode' => ['required', 'string', 'max:50'],
        ], [], [
            'voucherCode' => 'kode voucher',
        ]);

        $code = strtoupper(trim($this->voucherCode));

        $voucher = Voucher::query()
            ->where('code', $code)
            ->first();

        if (! $voucher) {
            $this->addError('voucherCode', 'Kode voucher tidak ditemukan.');
            return;
        }

        $subtotalAmount = $this->currentSubtotalAmount();

        if ($error = $this->voucherError($voucher, $customerId, $subtotalAmount)) {
            $this->addError('voucherCode', $error);
            return;
        }

        $discountAmount = min(
            $voucher->calculateDiscount($subtotalAmount),
            max(0, $subtotalAmount - 1)
        );

        if ($discountAmount <= 0) {
            $this->addError('voucherCode', 'Voucher tidak menghasilkan potongan.');
            return;
        }

        $this->appliedVoucherId = $voucher->id;
        $this->appliedVoucherCode = $voucher->code;
        $this->voucherCode = $voucher->code;
        $this->discountAmount = $discountAmount;
    }

    public function removeVoucher(): void
    {
        $this->clearVoucher();
    }

    private function currentSubtotalAmount(): int
    {
        $gateway = PaymentGateway::ensureMidtrans();

        $selectedProduct = $this->game->products->firstWhere('id', $this->productId);

        $productPrice = (int) ($selectedProduct?->selling_price ?? 0);
        $adminFee = $this->calculateAdminFee($gateway, $productPrice);

        return $productPrice + $adminFee;
    }

    private function voucherError(Voucher $voucher, ?int $customerId, int $amount): ?string
    {
        if (! $customerId) {
            return 'Masuk dulu untuk menggunakan kode voucher.';
        }

        if (! $voucher->isUsableNow()) {
            return 'Voucher tidak aktif atau sudah berakhir.';
        }

        if ($amount < (int) $voucher->min_order_amount) {
            return 'Minimal transaksi untuk voucher ini Rp ' . number_format((int) $voucher->min_order_amount, 0, ',', '.') . '.';
        }

        if ($voucher->per_customer_limit !== null) {
            $usedByCustomer = Order::query()
                ->where('customer_id', $customerId)
                ->where('voucher_id', $voucher->id)
                ->whereIn('status', ['pending', 'paid'])
                ->count();

            if ($usedByCustomer >= $voucher->per_customer_limit) {
                return 'Voucher ini sudah mencapai batas pemakaian untuk akun kamu.';
            }
        }

        return null;
    }

    private function clearVoucher(): void
    {
        $this->voucherCode = '';
        $this->appliedVoucherId = null;
        $this->appliedVoucherCode = null;
        $this->discountAmount = 0;
        $this->resetErrorBag('voucherCode');
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

        $subtotalAmount = $productPrice + $adminFee;

        $discountAmount = $subtotalAmount > 0
            ? min($this->discountAmount, max(0, $subtotalAmount - 1))
            : 0;

        $totalAmount = $subtotalAmount > 0
            ? max(1, $subtotalAmount - $discountAmount)
            : 0;

        return view('livewire.customer.checkout-page', [
            'game' => $this->game,
            'products' => $products,
            'paymentGateways' => $paymentGateways,
            'selectedProduct' => $selectedProduct,
            'selectedGateway' => $selectedGateway,
            'productPrice' => $productPrice,
            'adminFee' => $adminFee,
            'subtotalAmount' => $subtotalAmount,
            'discountAmount' => $discountAmount,
            'appliedVoucherCode' => $this->appliedVoucherCode,
            'totalAmount' => $totalAmount,
        ])->layout('layouts.public', [
            'title' => 'Checkout ' . $this->game->name,
        ]);
    }
}
