<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

class BestSellingProductsChart extends ChartWidget
{
    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = 'half';

    protected static ?string $heading = 'Produk Terlaris';

    protected static ?string $description = 'Produk dengan transaksi terbanyak';

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $products = Order::query()
            ->where('status', 'paid')
            ->selectRaw("COALESCE(NULLIF(product_name, ''), 'Tanpa Nama Produk') as product_name")
            ->selectRaw('COUNT(*) as transactions')
            ->groupBy('product_name')
            ->orderByDesc('transactions')
            ->limit(7)
            ->get();

        $labels = $products->pluck('product_name')->all();
        $data = $products->pluck('transactions')->map(fn ($value) => (int) $value)->all();

        return [
            'datasets' => [[
                'label' => 'Jumlah Transaksi',
                'data' => $data,
                'backgroundColor' => '#10b981',
            ]],
            'labels' => $labels,
        ];
    }
}
