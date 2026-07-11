<?php

namespace App\Livewire\Customer;

use App\Models\Order;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class OrderLookupPage extends Component
{
    public string $invoice = '';

    public bool $orderNotFound = false;

    public function updatedInvoice(): void
    {
        $this->orderNotFound = false;
    }

    public function checkOrder(): ?\Illuminate\Http\RedirectResponse
    {
        $this->validate([
            'invoice' => ['required', 'string', 'max:100'],
        ], [
            'invoice.required' => 'Nomor invoice wajib diisi.',
            'invoice.string' => 'Nomor invoice harus berupa teks.',
            'invoice.max' => 'Nomor invoice maksimal 100 karakter.',
        ]);

        $this->invoice = strtoupper(trim($this->invoice));
        $this->orderNotFound = false;

        $order = Order::query()
            ->where('invoice_number', $this->invoice)
            ->first();

        if (! $order) {
            $this->orderNotFound = true;

            return null;
        }

        return redirect()->route('orders.show', [
            'invoice' => $this->invoice,
        ]);
    }

    public function render(): View
    {
        return view('livewire.customer.order-lookup-page')->layout('layouts.public', [
            'title' => 'Cek Pesanan',
        ]);
    }
}
