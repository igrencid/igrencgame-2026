<?php

namespace App\Filament\Admin\Resources;

use App\Models\ContentPage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ContentPageResource extends Resource
{
    protected static ?string $model = ContentPage::class;

    protected static ?string $navigationGroup = 'Konten';

    protected static ?string $navigationLabel = 'Halaman Legal';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Konten Halaman')
                    ->description('Isi judul, slug, dan konten halaman.')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Judul')
                            ->required()
                            ->live()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->unique('content_pages', 'slug', ignoreRecord: true)
                            ->helperText('Gunakan "terms" untuk Syarat & Ketentuan dan "privacy" untuk Kebijakan Privasi.')
                            ->columnSpanFull(),

                        Forms\Components\RichEditor::make('content')
                            ->label('Konten')
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('SEO')
                    ->description('Atur meta tag untuk SEO.')
                    ->schema([
                        Forms\Components\TextInput::make('meta_title')
                            ->label('Meta Title')
                            ->maxLength(255),

                        Forms\Components\Textarea::make('meta_description')
                            ->label('Meta Description')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Publikasi')
                    ->description('Kontrol status publikasi halaman.')
                    ->schema([
                        Forms\Components\Toggle::make('is_published')
                            ->label('Published')
                            ->default(true),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_published')
                    ->label('Published')
                    ->boolean(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('Status'),
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
            'index' => \App\Filament\Admin\Resources\ContentPageResource\Pages\ListContentPages::route('/'),
            'create' => \App\Filament\Admin\Resources\ContentPageResource\Pages\CreateContentPage::route('/create'),
            'edit' => \App\Filament\Admin\Resources\ContentPageResource\Pages\EditContentPage::route('/{record}/edit'),
        ];
    }
}
