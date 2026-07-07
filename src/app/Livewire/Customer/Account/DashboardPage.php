<?php

namespace App\Livewire\Customer\Account;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class DashboardPage extends Component
{
    #[Layout('layouts.public')]
    public function render()
    {
        $customer = Auth::guard('customer')->user();

        return view('livewire.customer.account.dashboard-page', [
            'customer' => $customer,
            'totalOrders' => $customer->orders()->count(),
            'paidOrders' => $customer->orders()->where('status', 'paid')->count(),
            'pendingOrders' => $customer->orders()->where('status', 'pending')->count(),
        ]);
    }
}
