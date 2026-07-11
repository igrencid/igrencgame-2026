<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PromoResource\Pages;
use App\Models\Promo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PromoResource extends Resource
{
    protected static ?string $model = Promo::class;

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';

    protected static ?string $navigationLabel = 'Promo';

    protected static ?string $navigationGroup = 'Manajemen Konten';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\TextInput::make('judul')
                    ->required()
                    ->maxLength(255),


                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),


                Forms\Components\Textarea::make('deskripsi')
                    ->required()
                    ->rows(5),


                Forms\Components\FileUpload::make('gambar')
                    ->image()
                    ->directory('promo')
                    ->nullable(),


                Forms\Components\TextInput::make('kode_promo')
                    ->maxLength(50)
                    ->nullable(),


                Forms\Components\TextInput::make('diskon')
                    ->numeric()
                    ->default(0),


                Forms\Components\DatePicker::make('tanggal_mulai')
                    ->required(),


                Forms\Components\DatePicker::make('tanggal_akhir')
                    ->required(),


                Forms\Components\Toggle::make('status')
                    ->default(true),

            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\ImageColumn::make('gambar')
                    ->label('Gambar'),

                Tables\Columns\TextColumn::make('judul')
                    ->searchable(),

                Tables\Columns\TextColumn::make('kode_promo'),

                Tables\Columns\TextColumn::make('diskon')
                    ->suffix('%'),

                Tables\Columns\TextColumn::make('tanggal_mulai')
                    ->date(),

                Tables\Columns\TextColumn::make('tanggal_akhir')
                    ->date(),

                Tables\Columns\IconColumn::make('status')
                    ->boolean(),

            ])
            ->filters([
                //
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
        return [];
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPromos::route('/'),
            'create' => Pages\CreatePromo::route('/create'),
            'edit' => Pages\EditPromo::route('/{record}/edit'),
        ];
    }
}