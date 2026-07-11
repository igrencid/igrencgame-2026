<?php

namespace App\Livewire;

use App\Models\Promo;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class PromoSection extends Component
{
    public function render(): View
    {
        $today = today()->toDateString();

        $promos = Promo::query()
            ->where('status', true)
            ->whereDate('tanggal_mulai', '<=', $today)
            ->whereDate('tanggal_akhir', '>=', $today)
            ->latest('tanggal_mulai')
            ->limit(3)
            ->get();

        return view('livewire.promo-section', [
            'promos' => $promos,
        ]);
    }
}
