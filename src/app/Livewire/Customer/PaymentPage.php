<?php

namespace App\Livewire\Customer;

use App\Models\Order;
use App\Services\Orders\OrderExpiryService;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class PaymentPage extends Component
{
    public Order $order;

    public int $remainingSeconds = 0;

    public function mount(string $invoice): void
    {
        $this->order = Order::query()
            ->where('invoice_number', $invoice)
            ->with(['payment', 'paymentGateway'])
            ->firstOrFail();

        $this->applyExpiration();
    }

    public function refreshPaymentStatus(): void
    {
        $this->applyExpiration();
    }

    private function applyExpiration(): void
    {
        $expiryService = app(OrderExpiryService::class);

        $this->order = $expiryService->expireIfNeeded($this->order);
        $this->remainingSeconds = $expiryService->secondsRemaining($this->order);
    }

    public function render(): View
    {
        return view('livewire.customer.payment-page', [
            'order' => $this->order,
            'remainingSeconds' => $this->remainingSeconds,
        ])->layout('layouts.public', [
            'title' => 'Pembayaran ' . $this->order->invoice_number,
        ]);
    }
}
