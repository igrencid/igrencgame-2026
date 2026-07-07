<?php

namespace App\Livewire\Customer\Account;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class OrdersPage extends Component
{
    use WithPagination;

    #[Layout('layouts.public')]
    public function render()
    {
        $orders = Auth::guard('customer')
            ->user()
            ->orders()
            ->latest()
            ->paginate(10);

        return view('livewire.customer.account.orders-page', [
            'orders' => $orders,
        ]);
    }
}
