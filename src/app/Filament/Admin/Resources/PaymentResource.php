<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PaymentResource\Pages;
use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationGroup = 'Transaksi';

    protected static ?string $navigationLabel = 'Payments';

    protected static ?string $modelLabel = 'Payment';

    protected static ?string $pluralModelLabel = 'Payments';

    protected static ?int $navigationSort = 2;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Payment')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('payment_number')
                                    ->label('Payment Number')
                                    ->disabled(),

                                Forms\Components\Select::make('status')
                                    ->label('Status Payment')
                                    ->options([
                                        'pending' => 'Pending',
                                        'settlement' => 'Settlement',
                                        'capture' => 'Capture',
                                        'deny' => 'Deny',
                                        'cancel' => 'Cancel',
                                        'expire' => 'Expire',
                                        'refund' => 'Refund',
                                        'failure' => 'Failure',
                                    ])
                                    ->required(),

                                Forms\Components\TextInput::make('provider')
                                    ->label('Provider')
                                    ->disabled(),

                                Forms\Components\TextInput::make('payment_method')
                                    ->label('Payment Method'),

                                Forms\Components\TextInput::make('amount')
                                    ->label('Amount')
                                    ->prefix('Rp')
                                    ->disabled(),

                                Forms\Components\TextInput::make('transaction_id')
                                    ->label('Transaction ID'),

                                Forms\Components\TextInput::make('fraud_status')
                                    ->label('Fraud Status'),

                                Forms\Components\DateTimePicker::make('paid_at')
                                    ->label('Dibayar Pada'),

                                Forms\Components\DateTimePicker::make('expired_at')
                                    ->label('Expired Pada'),
                            ]),
                    ]),

                Forms\Components\Section::make('Midtrans Data')
                    ->schema([
                        Forms\Components\Textarea::make('snap_token')
                            ->label('Snap Token')
                            ->disabled()
                            ->rows(3),

                        Forms\Components\Textarea::make('redirect_url')
                            ->label('Redirect URL')
                            ->disabled()
                            ->rows(2),

                        Forms\Components\KeyValue::make('raw_response')
                            ->label('Raw Response')
                            ->disabled()
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('payment_number')
                    ->label('Payment Number')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('order.invoice_number')
                    ->label('Invoice')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('provider')
                    ->label('Provider')
                    ->badge(),

                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Method')
                    ->placeholder('-')
                    ->searchable(),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Amount')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'settlement', 'capture' => 'success',
                        'pending' => 'warning',
                        'deny', 'cancel', 'expire', 'failure' => 'danger',
                        'refund' => 'info',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('transaction_id')
                    ->label('Transaction ID')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('paid_at')
                    ->label('Paid At')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status Payment')
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
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayments::route('/'),
            'view' => Pages\ViewPayment::route('/{record}'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}