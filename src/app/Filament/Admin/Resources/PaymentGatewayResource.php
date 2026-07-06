<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PaymentGatewayResource\Pages;
use App\Models\PaymentGateway;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PaymentGatewayResource extends Resource
{
    protected static ?string $model = PaymentGateway::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationGroup = 'Pembayaran';

    protected static ?string $navigationLabel = 'Payment Gateways';

    protected static ?string $modelLabel = 'Payment Gateway';

    protected static ?string $pluralModelLabel = 'Payment Gateways';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Gateway Utama')
                ->description('Konfigurasi gateway pembayaran. Secret key Midtrans tetap disimpan di file .env.')
                ->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Gateway')
                            ->placeholder('Midtrans Sandbox')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('provider')
                            ->label('Provider')
                            ->options([
                                'midtrans' => 'Midtrans',
                            ])
                            ->default('midtrans')
                            ->required()
                            ->native(false),

                        Forms\Components\Select::make('mode')
                            ->label('Mode')
                            ->options([
                                'sandbox' => 'Sandbox',
                                'production' => 'Production',
                            ])
                            ->default('sandbox')
                            ->required()
                            ->native(false),

                        Forms\Components\TextInput::make('display_label')
                            ->label('Label Tampilan')
                            ->placeholder('Midtrans')
                            ->default('Midtrans')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('sort_order')
                            ->label('Urutan')
                            ->numeric()
                            ->default(1)
                            ->required(),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true)
                            ->required(),
                    ]),
                ]),

            Forms\Components\Section::make('Fee dan Batas Transaksi')
                ->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\Select::make('fee_type')
                            ->label('Tipe Fee')
                            ->options([
                                'fixed' => 'Fixed',
                                'percentage' => 'Percentage',
                            ])
                            ->default('fixed')
                            ->required()
                            ->native(false),

                        Forms\Components\TextInput::make('fee_value')
                            ->label('Nilai Fee')
                            ->numeric()
                            ->default(2500)
                            ->required(),

                        Forms\Components\TextInput::make('minimum_amount')
                            ->label('Minimum Transaksi')
                            ->prefix('Rp')
                            ->numeric()
                            ->default(0)
                            ->required(),

                        Forms\Components\TextInput::make('maximum_amount')
                            ->label('Maximum Transaksi')
                            ->prefix('Rp')
                            ->numeric()
                            ->nullable(),
                    ]),
                ]),

            Forms\Components\Section::make('Instruksi')
                ->schema([
                    Forms\Components\Textarea::make('instruction')
                        ->label('Instruksi Pembayaran')
                        ->placeholder('Customer akan diarahkan ke halaman pembayaran Midtrans.')
                        ->default('Bayar melalui Midtrans Snap.')
                        ->rows(4)
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Gateway')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('provider')
                    ->label('Provider')
                    ->badge(),

                Tables\Columns\TextColumn::make('mode')
                    ->label('Mode')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'production' => 'success',
                        default => 'warning',
                    }),

                Tables\Columns\TextColumn::make('display_label')
                    ->label('Label'),

                Tables\Columns\TextColumn::make('fee_type')
                    ->label('Tipe Fee')
                    ->badge(),

                Tables\Columns\TextColumn::make('fee_value')
                    ->label('Fee')
                    ->formatStateUsing(function ($record) {
                        if ($record->fee_type === 'percentage') {
                            return $record->fee_value . '%';
                        }

                        return 'Rp ' . number_format((int) $record->fee_value, 0, ',', '.');
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('minimum_amount')
                    ->label('Min. Transaksi')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('maximum_amount')
                    ->label('Max. Transaksi')
                    ->money('IDR')
                    ->placeholder('-')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diupdate')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order')
            ->filters([
                Tables\Filters\SelectFilter::make('provider')
                    ->label('Provider')
                    ->options([
                        'midtrans' => 'Midtrans',
                    ]),

                Tables\Filters\SelectFilter::make('mode')
                    ->label('Mode')
                    ->options([
                        'sandbox' => 'Sandbox',
                        'production' => 'Production',
                    ]),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Aktif'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPaymentGateways::route('/'),
            'create' => Pages\CreatePaymentGateway::route('/create'),
            'edit' => Pages\EditPaymentGateway::route('/{record}/edit'),
        ];
    }
}
