<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $totalOrders = Order::query()->count();
        $revenuePaid = Order::query()->where('status', 'paid')->sum('total_amount');
        $pendingOrders = Order::query()->where('status', 'pending')->count();
        $paidOrders = Order::query()->where('status', 'paid')->count();
        $failedOrders = Order::query()->where('status', 'failed')->count();
        $expiredOrders = Order::query()->where('status', 'expired')->count();

        return [
            Stat::make('Total Pesanan', number_format($totalOrders))
                ->description('Jumlah seluruh order yang tercatat')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('info'),

            Stat::make('Omzet Dibayar', 'Rp ' . number_format((float) $revenuePaid, 0, ',', '.'))
                ->description('Revenue dari order berstatus paid')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),

            Stat::make('Pesanan Pending', number_format($pendingOrders))
                ->description('Order menunggu diproses admin')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Pesanan Paid', number_format($paidOrders))
                ->description('Order yang sudah dibayar')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Pesanan Gagal', number_format($failedOrders))
                ->description('Order yang gagal diproses')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),

            Stat::make('Pesanan Expired', number_format($expiredOrders))
                ->description('Order yang melewati batas waktu')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('gray'),
        ];
    }
}
