<?php

namespace App\Livewire\Customer;

use App\Models\Order;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class OrderStatusPage extends Component
{
    public Order $order;

    public function mount(string $invoice): void
    {
        $this->order = Order::query()
            ->where('invoice_number', $invoice)
            ->with(['payment', 'paymentGateway'])
            ->firstOrFail();
    }

    public function getStatusLabelProperty(): string
    {
        return match ($this->order->status) {
            'pending' => 'Menunggu Pembayaran',
            'paid' => 'Sudah Dibayar',
            'processing' => 'Sedang Pesanan Diproses',
            'success' => 'Selesai',
            'failed' => 'Gagal',
            'expired' => 'Kedaluwarsa',
            default => ucfirst($this->order->status),
        };
    }

    public function getStatusBadgeClassProperty(): string
    {
        return match ($this->order->status) {
            'pending' => 'bg-amber-50 text-amber-700 ring-amber-200',
            'paid', 'processing' => 'bg-blue-50 text-blue-700 ring-blue-200',
            'success' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
            'failed', 'expired' => 'bg-rose-50 text-rose-700 ring-rose-200',
            default => 'bg-slate-50 text-slate-700 ring-slate-200',
        };
    }

    public function render(): View
    {
        return view('livewire.customer.order-status-page')
            ->layout('layouts.public', [
                'title' => 'Cek Pesanan ' . $this->order->invoice_number,
            ]);
    }
}