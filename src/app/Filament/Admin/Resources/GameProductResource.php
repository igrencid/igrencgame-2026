<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\GameProductResource\Pages;
use App\Models\GameProduct;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class GameProductResource extends Resource
{
    protected static ?string $model = GameProduct::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'Katalog';

    protected static ?string $navigationLabel = 'Nominal Top Up';

    protected static ?string $modelLabel = 'Nominal Top Up';

    protected static ?string $pluralModelLabel = 'Nominal Top Up';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Produk')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('game_id')
                                    ->label('Game')
                                    ->relationship('game', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Forms\Components\TextInput::make('name')
                                    ->label('Nama Nominal')
                                    ->placeholder('86 Diamonds')
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('code')
                                    ->label('Kode Produk')
                                    ->placeholder('ML86')
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('base_price')
                                    ->label('Harga Modal')
                                    ->prefix('Rp')
                                    ->numeric()
                                    ->default(0)
                                    ->required(),

                                Forms\Components\TextInput::make('selling_price')
                                    ->label('Harga Jual')
                                    ->prefix('Rp')
                                    ->numeric()
                                    ->default(0)
                                    ->required(),

                                Forms\Components\TextInput::make('sort_order')
                                    ->label('Urutan')
                                    ->numeric()
                                    ->default(0)
                                    ->required(),

                                Forms\Components\Toggle::make('is_active')
                                    ->label('Aktif')
                                    ->default(true)
                                    ->required(),
                            ]),

                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(3)
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

                Tables\Columns\TextColumn::make('game.name')
                    ->label('Game')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nominal')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('code')
                    ->label('Kode')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('base_price')
                    ->label('Modal')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('selling_price')
                    ->label('Harga Jual')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order')
            ->filters([
                Tables\Filters\SelectFilter::make('game_id')
                    ->label('Game')
                    ->relationship('game', 'name')
                    ->searchable()
                    ->preload(),

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
            'index' => Pages\ListGameProducts::route('/'),
            'create' => Pages\CreateGameProduct::route('/create'),
            'edit' => Pages\EditGameProduct::route('/{record}/edit'),
        ];
    }
}