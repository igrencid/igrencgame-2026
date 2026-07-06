<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

class RevenueTrendChart extends ChartWidget
{
    protected static ?int $sort = 6;

    protected int|string|array $columnSpan = 'half';

    protected static ?string $heading = 'Omzet 7 Hari';

    protected static ?string $description = 'Perkembangan omzet dari order berstatus paid';

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $startDate = now()->subDays(6)->startOfDay();
        $endDate = now()->endOfDay();

        $revenues = Order::query()
            ->where('status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw("COALESCE(DATE(paid_at), DATE(created_at)) as date")
            ->selectRaw('SUM(total_amount) as revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('revenue', 'date')
            ->all();

        $labels = [];
        $data = [];

        for ($i = 0; $i < 7; $i++) {
            $date = $startDate->copy()->addDays($i)->toDateString();
            $labels[] = $startDate->copy()->addDays($i)->format('d M');
            $data[] = (float) ($revenues[$date] ?? 0);
        }

        return [
            'datasets' => [[
                'label' => 'Omzet',
                'data' => $data,
                'borderColor' => '#10b981',
                'backgroundColor' => 'rgba(16, 185, 129, 0.15)',
                'fill' => true,
                'tension' => 0.35,
            ]],
            'labels' => $labels,
        ];
    }
}
