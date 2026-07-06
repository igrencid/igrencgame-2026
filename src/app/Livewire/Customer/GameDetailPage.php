<?php

namespace App\Livewire\Customer;

use App\Models\Game;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class GameDetailPage extends Component
{
    public Game $game;

    public function mount(string $slug): void
    {
        $this->game = Game::query()
            ->active()
            ->where('slug', $slug)
            ->with([
                'products' => function ($query) {
                    $query
                        ->where('is_active', true)
                        ->orderBy('sort_order')
                        ->orderBy('selling_price');
                },
                'inputFields' => function ($query) {
                    $query
                        ->where('is_active', true)
                        ->orderBy('sort_order');
                },
            ])
            ->firstOrFail();
    }

    public function render(): View
    {
        return view('livewire.customer.game-detail-page')
            ->layout('layouts.public', [
                'title' => $this->game->name,
            ]);
    }
}