<?php

namespace App\Livewire\Customer;

use App\Models\Game;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class TopUpPage extends Component
{
    use WithPagination;

    public string $search = '';

    public string $category = 'semua';

    public bool $popularOnly = false;

    public bool $supportsCategory = false;

    public bool $supportsPopular = false;

    public bool $supportsSortOrder = false;

    protected $queryString = [
        'search' => [
            'except' => '',
        ],
        'category' => [
            'except' => 'semua',
        ],
        'popularOnly' => [
            'except' => false,
            'as' => 'populer',
        ],
    ];

    public function mount(): void
    {
        $this->supportsCategory = Schema::hasColumn('games', 'category');
        $this->supportsPopular = Schema::hasColumn('games', 'is_popular');
        $this->supportsSortOrder = Schema::hasColumn('games', 'sort_order');
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedCategory(): void
    {
        $this->resetPage();
    }

    public function updatedPopularOnly(): void
    {
        $this->resetPage();
    }

    public function selectCategory(string $category): void
    {
        if (
            $category !== 'semua'
            && ! $this->categories()->contains($category)
        ) {
            return;
        }

        $this->category = $category;
        $this->resetPage();
    }

    public function togglePopular(): void
    {
        if (! $this->supportsPopular) {
            return;
        }

        $this->popularOnly = ! $this->popularOnly;
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset([
            'search',
            'popularOnly',
        ]);

        $this->category = 'semua';

        $this->resetPage();
    }

    private function categories(): Collection
    {
        if (! $this->supportsCategory) {
            return collect();
        }

        return Game::query()
            ->active()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category')
            ->filter()
            ->values();
    }

    private function games(): LengthAwarePaginator
    {
        $search = trim($this->search);

        $query = Game::query()
            ->active()
            ->withMin([
                'products as minimum_price' => fn (Builder $query) => $query
                    ->where('is_active', true),
            ], 'selling_price')
            ->when(
                $search !== '',
                fn (Builder $query) => $query->where(
                    'name',
                    'like',
                    '%' . $search . '%'
                )
            )
            ->when(
                $this->supportsCategory && $this->category !== 'semua',
                fn (Builder $query) => $query->where(
                    'category',
                    $this->category
                )
            )
            ->when(
                $this->supportsPopular && $this->popularOnly,
                fn (Builder $query) => $query->where(
                    'is_popular',
                    true
                )
            );

        if ($this->supportsPopular) {
            $query->orderByDesc('is_popular');
        }

        if ($this->supportsSortOrder) {
            $query->orderBy('sort_order');
        }

        $games = $query
            ->orderBy('name')
            ->paginate(15);

        $games->getCollection()->transform(function (Game $game): Game {
            $game->setAttribute(
                'display_image_url',
                $this->resolveImageUrl($game)
            );

            return $game;
        });

        return $games;
    }

    private function resolveImageUrl(Game $game): ?string
    {
        foreach ([
            'image_url',
            'cover_url',
            'thumbnail_url',
        ] as $attribute) {
            $url = $game->getAttribute($attribute);

            if (is_string($url) && trim($url) !== '') {
                return $url;
            }
        }

        foreach ([
            'image',
            'cover',
            'cover_image',
            'thumbnail',
            'icon',
        ] as $attribute) {
            $path = $game->getAttribute($attribute);

            if (! is_string($path) || trim($path) === '') {
                continue;
            }

            if (Str::startsWith($path, ['http://', 'https://'])) {
                return $path;
            }

            if (Str::startsWith($path, ['/storage/', 'storage/'])) {
                return asset(ltrim($path, '/'));
            }

            return Storage::disk('public')->url($path);
        }

        return null;
    }

    public function render(): View
    {
        return view('livewire.customer.top-up-page', [
            'games' => $this->games(),
            'categories' => $this->categories(),
        ])->layout('layouts.public', [
            'title' => 'Top Up Game',
        ]);
    }
}
