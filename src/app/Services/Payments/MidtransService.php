<?php

namespace App\Services\Payments;

use App\Models\Order;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = (bool) config('midtrans.is_production');
        Config::$isSanitized = (bool) config('midtrans.is_sanitized');
        Config::$is3ds = (bool) config('midtrans.is_3ds');
    }

    public function createSnapTransaction(Order $order): object
    {
        $itemDetails = [
            [
                'id' => (string) ($order->game_product_id ?? 'product'),
                'price' => (int) $order->product_price,
                'quantity' => 1,
                'name' => $order->product_name,
            ],
        ];

        if ((int) $order->admin_fee > 0) {
            $itemDetails[] = [
                'id' => 'admin-fee',
                'price' => (int) $order->admin_fee,
                'quantity' => 1,
                'name' => 'Admin Fee',
            ];
        }

        $params = [
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
        ];

        return Snap::createTransaction($params);
    }
}
