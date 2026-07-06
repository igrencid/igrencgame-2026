<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

class OrderStatusDoughnutChart extends ChartWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'half';

    protected static ?string $heading = 'Komposisi Status Pesanan';

    protected static ?string $description = 'Distribusi status order utama';

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getData(): array
    {
        $statuses = Order::query()
            ->pluck('status')
            ->filter(fn ($status) => ! empty($status));

        $counts = [
            'pending' => 0,
            'paid' => 0,
            'failed' => 0,
            'expired' => 0,
            'others' => 0,
        ];

        foreach ($statuses as $status) {
            if (array_key_exists($status, $counts)) {
                $counts[$status]++;
            } else {
                $counts['others']++;
            }
        }

        return [
            'datasets' => [[
                'label' => 'Jumlah Order',
                'data' => array_values($counts),
                'backgroundColor' => [
                    '#f59e0b',
                    '#10b981',
                    '#ef4444',
                    '#6b7280',
                    '#94a3b8',
                ],
            ]],
            'labels' => ['Pending', 'Paid', 'Failed', 'Expired', 'Lainnya'],
        ];
    }
}
