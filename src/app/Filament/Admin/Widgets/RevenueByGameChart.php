<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class RevenueByGameChart extends ChartWidget
{
    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Omzet per Game';

    protected static ?string $description = 'Revenue tertinggi dari order berstatus paid';

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $games = Order::query()
            ->where('status', 'paid')
            ->selectRaw("COALESCE(NULLIF(game_name, ''), 'Tanpa Nama Game') as game_name")
            ->selectRaw('SUM(total_amount) as revenue')
            ->groupBy('game_name')
            ->orderByDesc('revenue')
            ->limit(7)
            ->get();

        $labels = $games->pluck('game_name')->all();
        $data = $games->pluck('revenue')->map(fn ($value) => (float) $value)->all();

        return [
            'datasets' => [[
                'label' => 'Omzet',
                'data' => $data,
                'backgroundColor' => '#3b82f6',
            ]],
            'labels' => $labels,
        ];
    }
}
