<?php

namespace App\Livewire\Customer;

use App\Models\Game;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class HomePage extends Component
{
    public string $search = '';

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

        return view('livewire.customer.home-page', [
            'games' => $games,
        ])->layout('layouts.public', [
            'title' => 'IgrencGame - Top Up Game',
        ]);
    }
}