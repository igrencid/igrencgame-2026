<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class NeedsAttentionOverview extends BaseWidget
{
    protected static ?int $sort = 7;

    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $pendingOlderThan30Minutes = Order::query()
            ->where('status', 'pending')
            ->where('created_at', '<=', now()->subMinutes(30))
            ->count();

        $failedToday = Order::query()
            ->where('status', 'failed')
            ->whereDate('created_at', today())
            ->count();

        $paidToday = Order::query()
            ->where('status', 'paid')
            ->where(function ($query): void {
                $query->whereDate('paid_at', today())
                    ->orWhere(function ($subQuery): void {
                        $subQuery->whereNull('paid_at')
                            ->whereDate('created_at', today());
                    });
            })
            ->count();

        $revenueToday = Order::query()
            ->where('status', 'paid')
            ->where(function ($query): void {
                $query->whereDate('paid_at', today())
                    ->orWhere(function ($subQuery): void {
                        $subQuery->whereNull('paid_at')
                            ->whereDate('created_at', today());
                    });
            })
            ->sum('total_amount');

        return [
            Stat::make('Butuh Perhatian', number_format($pendingOlderThan30Minutes))
                ->description('Order pending lebih dari 30 menit')
                ->descriptionIcon('heroicon-m-exclamation-circle')
                ->color('warning'),

            Stat::make('Gagal Hari Ini', number_format($failedToday))
                ->description('Order gagal yang masuk hari ini')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),

            Stat::make('Paid Hari Ini', number_format($paidToday))
                ->description('Order paid yang masuk hari ini')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Omzet Hari Ini', 'Rp ' . number_format((float) $revenueToday, 0, ',', '.'))
                ->description('Revenue dari order paid hari ini')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('info'),
        ];
    }
}
