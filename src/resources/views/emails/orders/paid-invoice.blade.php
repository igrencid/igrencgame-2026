@component('mail::message')
# Invoice Metode Pembayaran

Halo **{{ $order->customer_name ?: 'Customer' }}**,

Metode Pembayaran untuk pesanan kamu sudah berhasil diterima.

@component('mail::panel')
**Invoice:** {{ $order->invoice_number }}

**Status:** Sudah Dibayar

**Tanggal Metode Pembayaran:** {{ $order->paid_at?->format('d M Y H:i') ?: now()->format('d M Y H:i') }}
@endcomponent

@component('mail::table')
| Keterangan | Detail |
| :--- | :--- |
| Game | {{ $order->game_name }} |
| Produk | {{ $order->product_name }} |
| Metode Metode Pembayaran | {{ $order->paymentGateway?->display_label ?: $order->paymentGateway?->name ?: '-' }} |
| Harga Produk | Rp {{ number_format($order->product_price, 0, ',', '.') }} |
| Biaya Admin | Rp {{ number_format($order->admin_fee, 0, ',', '.') }} |
| Total Bayar | **Rp {{ number_format($order->total_amount, 0, ',', '.') }}** |
@endcomponent

@php
    $customerInputs = is_array($order->customer_inputs)
        ? $order->customer_inputs
        : (json_decode($order->customer_inputs ?? '[]', true) ?: []);
@endphp

@if (count($customerInputs) > 0)
## Data Akun

@component('mail::table')
| Field | Nilai |
| :--- | :--- |
@foreach ($customerInputs as $label => $value)
| {{ $label }} | {{ $value ?: '-' }} |
@endforeach
@endcomponent
@endif

@component('mail::button', ['url' => route('orders.show', $order->invoice_number)])
Lihat Invoice
@endcomponent

Terima kasih sudah menggunakan **{{ config('app.name') }}**.

@endcomponent