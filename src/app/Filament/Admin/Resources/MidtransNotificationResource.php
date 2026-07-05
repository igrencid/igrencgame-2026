<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\MidtransNotificationResource\Pages;
use App\Models\MidtransNotification;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MidtransNotificationResource extends Resource
{
    protected static ?string $model = MidtransNotification::class;

    protected static ?string $navigationIcon = 'heroicon-o-bell-alert';

    protected static ?string $navigationGroup = 'Pembayaran';

    protected static ?string $navigationLabel = 'Midtrans Logs';

    protected static ?string $modelLabel = 'Midtrans Log';

    protected static ?string $pluralModelLabel = 'Midtrans Logs';

    protected static ?int $navigationSort = 2;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Callback')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('provider')
                                    ->disabled(),

                                Forms\Components\TextInput::make('order_id_from_provider')
                                    ->label('Order ID Provider')
                                    ->disabled(),

                                Forms\Components\TextInput::make('transaction_id')
                                    ->label('Transaction ID')
                                    ->disabled(),

                                Forms\Components\TextInput::make('transaction_status')
                                    ->label('Transaction Status')
                                    ->disabled(),

                                Forms\Components\TextInput::make('payment_type')
                                    ->label('Payment Type')
                                    ->disabled(),

                                Forms\Components\TextInput::make('fraud_status')
                                    ->label('Fraud Status')
                                    ->disabled(),

                                Forms\Components\TextInput::make('status_code')
                                    ->label('Status Code')
                                    ->disabled(),

                                Forms\Components\TextInput::make('gross_amount')
                                    ->label('Gross Amount')
                                    ->prefix('Rp')
                                    ->disabled(),

                                Forms\Components\Toggle::make('is_processed')
                                    ->label('Processed')
                                    ->disabled(),

                                Forms\Components\DateTimePicker::make('processed_at')
                                    ->label('Processed At')
                                    ->disabled(),
                            ]),
                    ]),

                Forms\Components\Section::make('Raw Data')
                    ->schema([
                        Forms\Components\KeyValue::make('headers')
                            ->label('Headers')
                            ->disabled()
                            ->columnSpanFull(),

                        Forms\Components\KeyValue::make('payload')
                            ->label('Payload')
                            ->disabled()
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('processing_error')
                            ->label('Processing Error')
                            ->disabled()
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\IconColumn::make('is_processed')
                    ->label('Processed')
                    ->boolean(),

                Tables\Columns\TextColumn::make('order.invoice_number')
                    ->label('Invoice')
                    ->searchable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('order_id_from_provider')
                    ->label('Order ID Provider')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('transaction_id')
                    ->label('Transaction ID')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('transaction_status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'settlement', 'capture' => 'success',
                        'pending' => 'warning',
                        'deny', 'cancel', 'expire', 'failure' => 'danger',
                        'refund' => 'info',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('payment_type')
                    ->label('Payment Type')
                    ->badge()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('gross_amount')
                    ->label('Amount')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Received At')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('transaction_status')
                    ->label('Transaction Status')
                    ->options([
                        'pending' => 'Pending',
                        'settlement' => 'Settlement',
                        'capture' => 'Capture',
                        'deny' => 'Deny',
                        'cancel' => 'Cancel',
                        'expire' => 'Expire',
                        'refund' => 'Refund',
                        'failure' => 'Failure',
                    ]),

                Tables\Filters\TernaryFilter::make('is_processed')
                    ->label('Processed'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMidtransNotifications::route('/'),
            'view' => Pages\ViewMidtransNotification::route('/{record}'),
        ];
    }
}