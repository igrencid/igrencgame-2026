<?php

namespace App\Filament\Admin\Resources\OrderResource\Pages;

use App\Filament\Admin\Resources\OrderResource;
use App\Models\Order;
use App\Models\Payment;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\DB;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('clearFailedOrders')
                ->label('Bersihkan Order Gagal')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Bersihkan order gagal?')
                ->modalDescription('Semua order dengan status failed beserta payment-nya akan dihapus. Data paid, pending, processing, dan success tidak akan ikut terhapus.')
                ->modalSubmitActionLabel('Ya, bersihkan')
                ->action(function (): void {
                    $deleted = DB::transaction(function () {
                        $orderIds = Order::query()
                            ->where('status', 'failed')
                            ->pluck('id');

                        if ($orderIds->isEmpty()) {
                            return 0;
                        }

                        Payment::query()
                            ->whereIn('order_id', $orderIds)
                            ->delete();

                        return Order::query()
                            ->whereIn('id', $orderIds)
                            ->delete();
                    });

                    Notification::make()
                        ->title('Order gagal dibersihkan')
                        ->body($deleted . ' order gagal berhasil dihapus.')
                        ->success()
                        ->send();
                }),

            Actions\Action::make('clearTestTransactions')
                ->label('Hapus Semua Transaksi Test')
                ->icon('heroicon-o-exclamation-triangle')
                ->color('gray')
                ->requiresConfirmation()
                ->modalHeading('Hapus semua transaksi test?')
                ->modalDescription('Ini akan menghapus SEMUA orders dan payments. Pakai hanya saat development/testing.')
                ->modalSubmitActionLabel('Ya, hapus semua')
                ->action(function (): void {
                    DB::transaction(function () {
                        Payment::query()->delete();
                        Order::query()->delete();
                    });

                    Notification::make()
                        ->title('Semua transaksi test dihapus')
                        ->body('Orders dan payments berhasil dikosongkan.')
                        ->success()
                        ->send();
                }),
        ];
    }
}
