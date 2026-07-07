<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\VoucherResource\Pages;
use App\Models\Voucher;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class VoucherResource extends Resource
{
    protected static ?string $model = Voucher::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $navigationGroup = 'Marketing';

    protected static ?string $navigationLabel = 'Voucher';

    protected static ?string $modelLabel = 'Voucher';

    protected static ?string $pluralModelLabel = 'Voucher';

    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Voucher')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('code')
                            ->label('Kode Voucher')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(50)
                            ->dehydrateStateUsing(fn ($state) => Str::upper(trim((string) $state))),

                        Forms\Components\TextInput::make('name')
                            ->label('Nama Voucher')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('type')
                            ->label('Tipe Diskon')
                            ->required()
                            ->options([
                                'fixed' => 'Nominal Rupiah',
                                'percentage' => 'Persentase',
                            ])
                            ->default('fixed'),

                        Forms\Components\TextInput::make('value')
                            ->label('Nilai Diskon')
                            ->numeric()
                            ->required()
                            ->minValue(1),

                        Forms\Components\TextInput::make('max_discount')
                            ->label('Maksimal Diskon')
                            ->numeric()
                            ->minValue(0)
                            ->nullable()
                            ->helperText('Dipakai untuk voucher persentase.'),

                        Forms\Components\TextInput::make('min_order_amount')
                            ->label('Minimal Belanja')
                            ->numeric()
                            ->required()
                            ->default(0)
                            ->minValue(0),

                        Forms\Components\TextInput::make('usage_limit')
                            ->label('Limit Total Pemakaian')
                            ->numeric()
                            ->minValue(1)
                            ->nullable(),

                        Forms\Components\TextInput::make('per_customer_limit')
                            ->label('Limit Per Pelanggan')
                            ->numeric()
                            ->minValue(1)
                            ->nullable(),

                        Forms\Components\DateTimePicker::make('starts_at')
                            ->label('Mulai Berlaku')
                            ->seconds(false)
                            ->nullable(),

                        Forms\Components\DateTimePicker::make('ends_at')
                            ->label('Berakhir')
                            ->seconds(false)
                            ->nullable(),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode')
                    ->badge()
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Tipe')
                    ->badge(),

                Tables\Columns\TextColumn::make('value')
                    ->label('Nilai')
                    ->formatStateUsing(fn ($state, Voucher $record): string => $record->type === 'percentage'
                        ? $record->value . '%'
                        : 'Rp ' . number_format($record->value, 0, ',', '.')),

                Tables\Columns\TextColumn::make('used_count')
                    ->label('Dipakai')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),

                Tables\Columns\TextColumn::make('ends_at')
                    ->label('Berakhir')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->placeholder('-'),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListVouchers::route('/'),
            'create' => Pages\CreateVoucher::route('/create'),
            'edit' => Pages\EditVoucher::route('/{record}/edit'),
        ];
    }
}
