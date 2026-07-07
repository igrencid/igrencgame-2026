<?php

namespace App\Services\Payments;

use App\Models\Order;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;
use RuntimeException;

class MidtransService
{
    private const SNAP_EXPIRY_MINUTES = 10;

    public function __construct()
    {
        $serverKey = trim((string) config('midtrans.server_key'));
        $clientKey = trim((string) config('midtrans.client_key'));

        if ($serverKey === '' || $clientKey === '') {
            throw new RuntimeException('Midtrans server key atau client key belum terbaca. Cek .env dan config/midtrans.php.');
        }

        Config::$serverKey = $serverKey;
        Config::$clientKey = $clientKey;
        Config::$isProduction = filter_var(config('midtrans.is_production'), FILTER_VALIDATE_BOOLEAN);
        Config::$isSanitized = filter_var(config('midtrans.is_sanitized'), FILTER_VALIDATE_BOOLEAN);
        Config::$is3ds = filter_var(config('midtrans.is_3ds'), FILTER_VALIDATE_BOOLEAN);
    }

    public function createSnapTransaction(Order $order): object
    {
        $itemDetails = [
            [
                'id' => (string) ($order->game_product_id ?? 'product'),
                'price' => (int) $order->product_price,
                'quantity' => 1,
                'name' => mb_substr($order->product_name ?: 'Produk Top Up', 0, 50),
            ],
        ];

        if ((int) $order->admin_fee > 0) {
            $itemDetails[] = [
                'id' => 'admin-fee',
                'price' => (int) $order->admin_fee,
                'quantity' => 1,
                'name' => 'Biaya Admin',
            ];
        }

        if ((int) ($order->discount_amount ?? 0) > 0) {
            $itemDetails[] = [
                'id' => 'voucher-discount',
                'price' => -1 * (int) $order->discount_amount,
                'quantity' => 1,
                'name' => mb_substr('Diskon Voucher ' . ($order->voucher_code ?: ''), 0, 50),
            ];
        }

        $startTime = ($order->created_at ?: now())
            ->copy()
            ->timezone(config('app.timezone'))
            ->format('Y-m-d H:i:s O');

        return Snap::createTransaction([
            'transaction_details' => [
                'order_id' => $order->invoice_number,
                'gross_amount' => (int) $order->total_amount,
            ],

            'customer_details' => [
                'first_name' => $order->customer_name ?: 'Customer',
                'email' => $order->customer_email,
                'phone' => $order->customer_phone,
            ],

            'item_details' => $itemDetails,

            'expiry' => [
                'start_time' => $startTime,
                'unit' => 'minute',
                'duration' => self::SNAP_EXPIRY_MINUTES,
            ],

            'callbacks' => [
                'finish' => route('payment.finish'),
            ],
        ]);
    }

    public function getTransactionStatus(string $orderId): object
    {
        return Transaction::status($orderId);
    }
}
