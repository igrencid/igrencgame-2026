<?php

namespace App\Filament\Admin\Resources\GameResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class InputFieldsRelationManager extends RelationManager
{
    protected static string $relationship = 'inputFields';

    protected static ?string $title = 'Input Fields';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(2)
                    ->schema([
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
                            ->maxLength(255),

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
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('label')
            ->columns([
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Urutan')
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
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
