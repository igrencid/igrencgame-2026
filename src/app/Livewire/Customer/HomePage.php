<?php

namespace App\Livewire\Customer;

use App\Models\Game;
use App\Models\GameProduct;
use App\Models\Order;
use App\Models\PaymentGateway;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class HomePage extends Component
{
    public string $search = '';

    public string $invoice = '';

    public function checkOrder()
    {
        $invoice = trim($this->invoice);

        if ($invoice === '') {
            return null;
        }

        return redirect()->route('orders.show', [
            'invoice' => $invoice,
        ]);
    }

    public function render(): View
    {
        $games = Game::query()
            ->active()
            ->when($this->search !== '', function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $stats = [
            'active_games' => Game::query()
                ->active()
                ->count(),

            'active_products' => GameProduct::query()
                ->where('is_active', true)
                ->count(),

            'active_payment_gateways' => PaymentGateway::query()
                ->where('is_active', true)
                ->count(),

            'processing_orders' => Order::query()
                ->whereIn('status', ['pending', 'paid', 'processing'])
                ->count(),
        ];

        return view('livewire.customer.home-page', [
            'games' => $games,
            'stats' => $stats,
        ])->layout('layouts.public', [
            'title' => 'Top Up Game',
        ]);
    }
}