<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\GameInputFieldResource\Pages;
use App\Models\GameInputField;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class GameInputFieldResource extends Resource
{
    protected static ?string $model = GameInputField::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $navigationIcon = 'heroicon-o-adjustments-horizontal';

    protected static ?string $navigationGroup = 'Katalog';

    protected static ?string $navigationLabel = 'Input Fields';

    protected static ?string $modelLabel = 'Input Field';

    protected static ?string $pluralModelLabel = 'Input Fields';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Field Customer')
                    ->description('Konfigurasi field yang nanti akan diisi oleh buyer saat checkout.')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('game_id')
                                    ->label('Game')
                                    ->relationship('game', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Forms\Components\TextInput::make('label')
                                    ->label('Label')
                                    ->placeholder('User ID')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(debounce: 500)
                                    ->afterStateUpdated(function (?string $state, Set $set): void {
                                        $set('name', Str::snake($state ?? ''));
                                    }),

                                Forms\Components\TextInput::make('name')
                                    ->label('Name / Key')
                                    ->placeholder('user_id')
                                    ->required()
                                    ->maxLength(255)
                                    ->helperText('Otomatis dari label. Dipakai sebagai key data order.'),

                                Forms\Components\Select::make('type')
                                    ->label('Tipe Input')
                                    ->options([
                                        'text' => 'Text',
                                        'number' => 'Number',
                                        'email' => 'Email',
                                        'password' => 'Password',
                                    ])
                                    ->default('text')
                                    ->required(),

                                Forms\Components\TextInput::make('placeholder')
                                    ->label('Placeholder')
                                    ->placeholder('Masukkan User ID')
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('helper_text')
                                    ->label('Helper Text')
                                    ->placeholder('Contoh: 123456789')
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('sort_order')
                                    ->label('Urutan')
                                    ->numeric()
                                    ->default(0)
                                    ->required(),

                                Forms\Components\Toggle::make('is_required')
                                    ->label('Wajib Diisi')
                                    ->default(true)
                                    ->required(),

                                Forms\Components\Toggle::make('is_active')
                                    ->label('Aktif')
                                    ->default(true)
                                    ->required(),
                            ]),
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

                Tables\Columns\TextColumn::make('label')
                    ->label('Label')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Key')
                    ->searchable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Tipe')
                    ->badge(),

                Tables\Columns\IconColumn::make('is_required')
                    ->label('Wajib')
                    ->boolean(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
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
            'index' => Pages\ListGameInputFields::route('/'),
            'create' => Pages\CreateGameInputField::route('/create'),
            'edit' => Pages\EditGameInputField::route('/{record}/edit'),
        ];
    }
}