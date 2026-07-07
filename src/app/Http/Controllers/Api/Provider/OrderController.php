<?php

namespace App\Http\Controllers\Api\Provider;

use App\Http\Controllers\Controller;
use App\Models\ApiProvider;
use App\Models\Game;
use App\Models\GameProduct;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentGateway;
use App\Services\Generators\OrderNumberGenerator;
use App\Services\Generators\PaymentNumberGenerator;
use App\Services\Payments\MidtransService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;

class OrderController extends Controller
{
    private const ORDER_EXPIRY_MINUTES = 10;

    public function store(Request $request): JsonResponse
    {
        /** @var ApiProvider $provider */
        $provider = $request->attributes->get('api_provider');

        $validator = Validator::make($request->all(), [
            'game_slug' => ['required', 'string', 'max:255'],
            'product_id' => ['required', 'integer'],
            'customer_name' => ['required', 'string', 'max:100'],
            'customer_email' => ['required', 'email', 'max:150'],
            'customer_phone' => ['required', 'string', 'max:30'],
            'inputs' => ['nullable', 'array'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        $game = Game::query()
            ->active()
            ->where('slug', $data['game_slug'])
            ->with([
                'inputFields' => fn ($query) => $query
                    ->where('is_active', true)
                    ->orderBy('sort_order'),
            ])
            ->first();

        if (! $game) {
            return response()->json([
                'message' => 'Game tidak ditemukan atau tidak aktif.',
            ], 404);
        }

        $product = GameProduct::query()
            ->whereKey($data['product_id'])
            ->where('game_id', $game->id)
            ->where('is_active', true)
            ->first();

        if (! $product) {
            return response()->json([
                'message' => 'Produk tidak ditemukan atau tidak aktif.',
            ], 422);
        }

        $inputRules = [];
        $inputAttributes = [];

        foreach ($game->inputFields as $field) {
            $inputRules['inputs.' . $field->id] = [
                $field->is_required ? 'required' : 'nullable',
                'string',
                'max:150',
            ];

            $inputAttributes['inputs.' . $field->id] = $field->label;
        }

        $inputValidator = Validator::make($request->all(), $inputRules, [], $inputAttributes);

        if ($inputValidator->fails()) {
            return response()->json([
                'message' => 'Validasi input game gagal.',
                'errors' => $inputValidator->errors(),
            ], 422);
        }

        $gateway = PaymentGateway::ensureMidtrans();
        $productPrice = (int) $product->selling_price;
        $adminFee = $this->calculateAdminFee($gateway, $productPrice);
        $totalAmount = $productPrice + $adminFee;
        $expiredAt = now()->addMinutes(self::ORDER_EXPIRY_MINUTES);

        $order = null;

        try {
            $order = DB::transaction(function () use ($provider, $game, $product, $gateway, $productPrice, $adminFee, $totalAmount, $expiredAt, $data, $request) {
                $order = Order::query()->create([
                    'api_provider_id' => $provider->id,
                    'customer_id' => null,
                    'invoice_number' => app(OrderNumberGenerator::class)->generate(),
                    'game_id' => $game->id,
                    'game_product_id' => $product->id,
                    'payment_gateway_id' => $gateway->id,
                    'game_name' => $game->name,
                    'product_name' => $product->name,
                    'customer_inputs' => $this->formatInputs($game, $request->input('inputs', [])),
                    'customer_name' => $data['customer_name'],
                    'customer_email' => $data['customer_email'],
                    'customer_phone' => $data['customer_phone'],
                    'product_price' => $productPrice,
                    'admin_fee' => $adminFee,
                    'discount_amount' => 0,
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

            return response()->json([
                'message' => 'Order berhasil dibuat.',
                'data' => $this->formatOrder($order->fresh(['payment', 'paymentGateway'])),
            ], 201);
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

            return response()->json([
                'message' => 'Gagal membuat order.',
            ], 500);
        }
    }

    public function show(Request $request, string $invoice): JsonResponse
    {
        /** @var ApiProvider $provider */
        $provider = $request->attributes->get('api_provider');

        $order = Order::query()
            ->with(['payment', 'paymentGateway'])
            ->where('invoice_number', $invoice)
            ->where('api_provider_id', $provider->id)
            ->first();

        if (! $order) {
            return response()->json([
                'message' => 'Order tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'data' => $this->formatOrder($order),
        ]);
    }

    private function calculateAdminFee(PaymentGateway $gateway, int $productPrice): int
    {
        if ($gateway->fee_type === 'percentage') {
            return (int) ceil($productPrice * ((int) $gateway->fee_value / 100));
        }

        return (int) $gateway->fee_value;
    }

    private function formatInputs(Game $game, array $inputs): array
    {
        return $game->inputFields
            ->mapWithKeys(function ($field) use ($inputs) {
                return [
                    $field->label => data_get($inputs, (string) $field->id),
                ];
            })
            ->toArray();
    }

    private function formatOrder(Order $order): array
    {
        return [
            'invoice_number' => $order->invoice_number,
            'status' => $order->status,

            'game' => [
                'id' => $order->game_id,
                'name' => $order->game_name,
            ],

            'product' => [
                'id' => $order->game_product_id,
                'name' => $order->product_name,
            ],

            'customer' => [
                'name' => $order->customer_name,
                'email' => $order->customer_email,
                'phone' => $order->customer_phone,
                'inputs' => $order->customer_inputs,
            ],

            'amounts' => [
                'product_price' => (int) $order->product_price,
                'admin_fee' => (int) $order->admin_fee,
                'discount_amount' => (int) ($order->discount_amount ?? 0),
                'total_amount' => (int) $order->total_amount,
            ],

            'payment' => [
                'provider' => $order->paymentGateway?->provider,
                'gateway_name' => $order->paymentGateway?->display_label ?: $order->paymentGateway?->name,
                'status' => $order->payment?->status,
                'snap_token' => $order->payment?->snap_token,
                'payment_url' => $order->payment?->redirect_url,
            ],

            'urls' => [
                'payment_page' => route('payment.show', $order->invoice_number),
                'status_page' => route('orders.show', $order->invoice_number),
            ],

            'expired_at' => $order->expired_at?->toISOString(),
            'created_at' => $order->created_at?->toISOString(),
            'paid_at' => $order->paid_at?->toISOString(),
        ];
    }
}
