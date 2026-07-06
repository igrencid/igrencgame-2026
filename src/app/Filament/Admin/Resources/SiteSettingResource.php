<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SiteSettingResource\Pages;
use App\Models\SiteSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class SiteSettingResource extends Resource
{
    protected static ?string $model = SiteSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Site Settings';

    protected static ?string $modelLabel = 'Site Setting';

    protected static ?string $pluralModelLabel = 'Site Settings';

    protected static ?int $navigationSort = 1;

    public static function canCreate(): bool
    {
        return SiteSetting::query()->count() === 0;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Brand Website')
                    ->schema([
                        Forms\Components\TextInput::make('site_name')
                            ->label('Nama Website')
                            ->required()
                            ->maxLength(100),

                        Forms\Components\TextInput::make('tagline')
                            ->label('Tagline')
                            ->maxLength(150),

                        Forms\Components\Textarea::make('seo_description')
                            ->label('SEO Description')
                            ->rows(3)
                            ->maxLength(300)
                            ->columnSpanFull(),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Logo dan Favicon')
                    ->schema([
                        Forms\Components\FileUpload::make('logo_path')
                            ->label('Logo Website')
                            ->disk('public')
                            ->directory('site/logo')
                            ->visibility('public')
                            ->image()
                            ->acceptedFileTypes([
                                'image/png',
                                'image/jpeg',
                                'image/webp',
                            ])
                            ->maxSize(4096)
                            ->imagePreviewHeight('140')
                            ->openable()
                            ->downloadable()
                            ->helperText('Upload PNG transparan. Rekomendasi rasio 1:1, minimal 512x512.'),

                        Forms\Components\FileUpload::make('favicon_path')
                            ->label('Favicon')
                            ->disk('public')
                            ->directory('site/favicon')
                            ->visibility('public')
                            ->image()
                            ->acceptedFileTypes([
                                'image/png',
                                'image/jpeg',
                                'image/webp',
                                'image/x-icon',
                            ])
                            ->maxSize(1024)
                            ->imagePreviewHeight('80')
                            ->openable()
                            ->downloadable()
                            ->helperText('Rekomendasi ukuran 64x64 atau 128x128. Pakai emblem sederhana.'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Customer Service')
                    ->description('Atur kontak customer service yang akan ditampilkan di public website.')
                    ->schema([
                        Forms\Components\TextInput::make('customer_service_whatsapp')
                            ->label('Nomor WhatsApp CS')
                            ->placeholder('6285813295317')
                            ->helperText('Gunakan format internasional tanpa tanda +, contoh 6285813295317')
                            ->maxLength(20),

                        Forms\Components\TextInput::make('customer_service_email')
                            ->label('Email Customer Service')
                            ->type('email')
                            ->placeholder('support@igrencgame.test')
                            ->maxLength(100),

                        Forms\Components\TextInput::make('customer_service_label')
                            ->label('Label Tombol CS')
                            ->default('Hubungi CS')
                            ->maxLength(50),

                        Forms\Components\TextInput::make('customer_service_working_hours')
                            ->label('Jam Operasional')
                            ->placeholder('Setiap hari 09.00 - 22.00 WIB')
                            ->maxLength(100),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo_path')
                    ->label('Logo')
                    ->disk('public')
                    ->size(48),

                Tables\Columns\TextColumn::make('site_name')
                    ->label('Nama Website')
                    ->searchable(),

                Tables\Columns\TextColumn::make('tagline')
                    ->label('Tagline')
                    ->placeholder('-'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir Diubah')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSiteSettings::route('/'),
            'create' => Pages\CreateSiteSetting::route('/create'),
            'edit' => Pages\EditSiteSetting::route('/{record}/edit'),
        ];
    }
}