<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\Promo;

class PromoPage extends Component
{
    public function render()
    {
        return view('livewire.customer.promo-page', [

            'promos' => Promo::where('status', true)
                ->whereDate('tanggal_mulai', '<=', now())
                ->whereDate('tanggal_akhir', '>=', now())
                ->latest()
                ->get(),

        ]);
    }
}