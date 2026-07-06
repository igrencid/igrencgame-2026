<?php

namespace App\Filament\Admin\Widgets;

use App\Filament\Admin\Resources\OrderResource;
use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestOrdersTable extends BaseWidget
{
    public static function canView(): bool
    {
        return false;
    }

    protected static ?int $sort = 8;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Order::query()
                    ->latest('created_at')
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('invoice_number')
                    ->label('Invoice')
                    ->url(fn (Order $record): string => OrderResource::getUrl('view', ['record' => $record]))
                    ->sortable(),

                TextColumn::make('customer_name')
                    ->label('Customer')
                    ->formatStateUsing(fn (?string $state, Order $record): string => $state ?: ($record->customer_email ?: '-')),

                TextColumn::make('game_name')
                    ->label('Game')
                    ->placeholder('-'),

                TextColumn::make('product_name')
                    ->label('Produk')
                    ->placeholder('-'),

                TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'paid', 'success' => 'success',
                        'pending' => 'warning',
                        'failed' => 'danger',
                        'expired' => 'gray',
                        'processing' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'paid' => 'Paid',
                        'success' => 'Success',
                        'pending' => 'Pending',
                        'failed' => 'Failed',
                        'expired' => 'Expired',
                        'processing' => 'Processing',
                        default => ucfirst((string) $state),
                    }),

                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->paginated([10])
            ->defaultPaginationPageOption(10);
    }
}
