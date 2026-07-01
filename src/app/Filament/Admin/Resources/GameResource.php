<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\GameResource\Pages;
use App\Filament\Admin\Resources\GameResource\RelationManagers\InputFieldsRelationManager;
use App\Filament\Admin\Resources\GameResource\RelationManagers\ProductsRelationManager;
use App\Models\Game;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class GameResource extends Resource
{
    protected static ?string $model = Game::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationGroup = 'Katalog';

    protected static ?string $navigationLabel = 'Games';

    protected static ?string $modelLabel = 'Game';

    protected static ?string $pluralModelLabel = 'Games';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Utama')
                    ->description('Data utama game yang akan tampil di katalog dan halaman detail.')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nama Game')
                                    ->placeholder('Mobile Legends')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(debounce: 500)
                                    ->afterStateUpdated(function (?string $state, Set $set): void {
                                        $set('slug', Str::slug($state ?? ''));
                                    }),

                                Forms\Components\TextInput::make('slug')
                                    ->label('Slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true)
                                    ->disabled()
                                    ->dehydrated()
                                    ->helperText('Otomatis dari nama game. Dipakai untuk URL detail game.'),

                                Forms\Components\TextInput::make('category')
                                    ->label('Kategori')
                                    ->placeholder('Mobile, PC, Console')
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('badge')
                                    ->label('Badge')
                                    ->placeholder('Populer, Promo, Baru')
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('sort_order')
                                    ->label('Urutan')
                                    ->numeric()
                                    ->default(0)
                                    ->minValue(0)
                                    ->required(),

                                Forms\Components\Toggle::make('is_active')
                                    ->label('Aktif')
                                    ->default(true)
                                    ->required(),
                            ]),
                    ]),

                Forms\Components\Section::make('Media')
                    ->description('Upload aset visual untuk katalog dan halaman detail game.')
                    ->schema([
                        Forms\Components\FileUpload::make('image_path')
                            ->label('Gambar / GIF Katalog')
                            ->disk('public')
                            ->directory('games/images')
                            ->visibility('public')
                            ->acceptedFileTypes([
                                'image/jpeg',
                                'image/png',
                                'image/webp',
                                'image/gif',
                            ])
                            ->maxSize(4096)
                            ->imagePreviewHeight('180')
                            ->downloadable()
                            ->openable()
                            ->helperText('Dipakai untuk card game di homepage. Format: JPG, PNG, WEBP, atau GIF.'),

                        Forms\Components\FileUpload::make('banner_path')
                            ->label('Banner Detail Game')
                            ->disk('public')
                            ->directory('games/banners')
                            ->visibility('public')
                            ->acceptedFileTypes([
                                'image/jpeg',
                                'image/png',
                                'image/webp',
                                'image/gif',
                            ])
                            ->maxSize(8192)
                            ->imagePreviewHeight('220')
                            ->downloadable()
                            ->openable()
                            ->helperText('Dipakai sebagai banner/header di halaman detail game.'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Deskripsi')
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi Game')
                            ->placeholder('Tulis deskripsi singkat tentang game ini.')
                            ->rows(4)
                            ->maxLength(1000)
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

                Tables\Columns\ImageColumn::make('image_path')
                    ->label('Gambar')
                    ->disk('public')
                    ->height(56)
                    ->square(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Game')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('category')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('badge')
                    ->label('Badge')
                    ->badge()
                    ->color('info')
                    ->placeholder('-'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diupdate')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order')
            ->filters([
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

    public static function getRelations(): array
    {
        return [
            ProductsRelationManager::class,
            InputFieldsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGames::route('/'),
            'create' => Pages\CreateGame::route('/create'),
            'edit' => Pages\EditGame::route('/{record}/edit'),
        ];
    }
}