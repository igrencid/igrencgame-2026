<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\OrderResource\Pages;
use App\Models\Order;
use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationGroup = 'Transaksi';

    protected static ?string $navigationLabel = 'Orders';

    protected static ?string $modelLabel = 'Order';

    protected static ?string $pluralModelLabel = 'Orders';

    protected static ?int $navigationSort = 1;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Order')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('invoice_number')
                                    ->label('Invoice')
                                    ->disabled(),

                                Forms\Components\Select::make('status')
                                    ->label('Status Order')
                                    ->options([
                                        'pending' => 'Pending',
                                        'paid' => 'Paid',
                                        'processing' => 'Processing',
                                        'success' => 'Success',
                                        'failed' => 'Failed',
                                        'expired' => 'Expired',
                                        'cancelled' => 'Cancelled',
                                    ])
                                    ->required(),

                                Forms\Components\TextInput::make('game_name')
                                    ->label('Game')
                                    ->disabled(),

                                Forms\Components\TextInput::make('product_name')
                                    ->label('Produk')
                                    ->disabled(),

                                Forms\Components\TextInput::make('product_price')
                                    ->label('Harga Produk')
                                    ->prefix('Rp')
                                    ->disabled(),

                                Forms\Components\TextInput::make('admin_fee')
                                    ->label('Admin Fee')
                                    ->prefix('Rp')
                                    ->disabled(),

                                Forms\Components\TextInput::make('total_amount')
                                    ->label('Total')
                                    ->prefix('Rp')
                                    ->disabled(),
                            ]),
                    ]),

                Forms\Components\Section::make('Data Customer')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('customer_name')
                                    ->label('Nama Customer'),

                                Forms\Components\TextInput::make('customer_email')
                                    ->label('Email'),

                                Forms\Components\TextInput::make('customer_phone')
                                    ->label('Nomor HP'),
                            ]),

                        Forms\Components\KeyValue::make('customer_inputs')
                            ->label('Input Game Customer')
                            ->disabled()
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Waktu')
                    ->schema([
                        Forms\Components\DateTimePicker::make('paid_at')
                            ->label('Dibayar Pada'),

                        Forms\Components\DateTimePicker::make('expired_at')
                            ->label('Expired Pada'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number')
                    ->label('Invoice')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('game_name')
                    ->label('Game')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('product_name')
                    ->label('Produk')
                    ->searchable(),

                Tables\Columns\TextColumn::make('customer_phone')
                    ->label('Nomor HP')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'success' => 'success',
                        'paid', 'processing' => 'info',
                        'failed', 'cancelled' => 'danger',
                        'expired' => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('payment.status')
                    ->label('Payment')
                    ->badge()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status Order')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'processing' => 'Processing',
                        'success' => 'Success',
                        'failed' => 'Failed',
                        'expired' => 'Expired',
                        'cancelled' => 'Cancelled',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->action(static function (Order $record): void {
                        DB::transaction(static function () use ($record): void {
                            Payment::query()
                                ->where('order_id', $record->id)
                                ->delete();

                            $record->delete();
                        });

                        Notification::make()
                            ->success()
                            ->title('Order berhasil dihapus')
                            ->body('Order dan payment terkait telah dihapus.')
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->color('danger'),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('deleteSelectedOrders')
                    ->label('Hapus Order Dipilih')
                    ->action(static function (Collection $records): void {
                        $orderIds = $records->pluck('id')->all();

                        DB::transaction(static function () use ($orderIds): void {
                            Payment::query()
                                ->whereIn('order_id', $orderIds)
                                ->delete();

                            Order::query()
                                ->whereIn('id', $orderIds)
                                ->delete();
                        });

                        Notification::make()
                            ->success()
                            ->title('Order berhasil dihapus')
                            ->body('Semua order yang dipilih dan payment terkait telah dihapus.')
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->color('danger'),

                Tables\Actions\BulkAction::make('deleteFailedExpiredSelectedOrders')
                    ->label('Hapus Gagal/Expired Dipilih')
                    ->action(static function (Collection $records): void {
                        $failedExpiredOrders = $records->filter(static fn (Order $order): bool => in_array($order->status, ['failed', 'expired'], true));

                        if ($failedExpiredOrders->isEmpty()) {
                            Notification::make()
                                ->warning()
                                ->title('Tidak ada data yang cocok')
                                ->body('Pilih order dengan status failed atau expired sebelum melakukan aksi ini.')
                                ->send();

                            return;
                        }

                        $orderIds = $failedExpiredOrders->pluck('id')->all();

                        DB::transaction(static function () use ($orderIds): void {
                            Payment::query()
                                ->whereIn('order_id', $orderIds)
                                ->delete();

                            Order::query()
                                ->whereIn('id', $orderIds)
                                ->delete();
                        });

                        Notification::make()
                            ->success()
                            ->title('Order gagal/expired berhasil dihapus')
                            ->body('Order failed/expired yang dipilih dan payment terkait telah dihapus.')
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->color('danger'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}