<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ApiProviderResource\Pages;
use App\Models\ApiProvider;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ApiProviderResource extends Resource
{
    protected static ?string $model = ApiProvider::class;

    protected static ?string $navigationIcon = 'heroicon-o-key';

    protected static ?string $navigationGroup = 'Administration';

    protected static ?string $navigationLabel = 'Provider Keys';

    protected static ?string $modelLabel = 'Provider Key';

    protected static ?string $pluralModelLabel = 'Provider Keys';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Provider')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Provider')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('contact_email')
                            ->label('Email Kontak')
                            ->email()
                            ->maxLength(255)
                            ->nullable(),

                        Forms\Components\Textarea::make('description')
                            ->label('Catatan')
                            ->columnSpanFull()
                            ->nullable(),

                        Forms\Components\TagsInput::make('permissions')
                            ->label('Permissions')
                            ->suggestions([
                                'games:read',
                                'products:read',
                                'orders:create',
                                'orders:read',
                            ])
                            ->default([
                                'games:read',
                                'products:read',
                                'orders:create',
                                'orders:read',
                            ])
                            ->columnSpanFull(),

                        Forms\Components\TagsInput::make('allowed_ips')
                            ->label('Allowed IP')
                            ->helperText('Kosongkan kalau semua IP boleh.')
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('rate_limit_per_minute')
                            ->label('Rate Limit / Menit')
                            ->numeric()
                            ->required()
                            ->default(60)
                            ->minValue(1),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ]),

                Forms\Components\Section::make('Key Info')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('key_prefix')
                            ->label('Key Prefix')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\DateTimePicker::make('last_used_at')
                            ->label('Terakhir Dipakai')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\TextInput::make('last_used_ip')
                            ->label('IP Terakhir')
                            ->disabled()
                            ->dehydrated(false),
                    ])
                    ->visible(fn (?ApiProvider $record): bool => filled($record)),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Provider')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('key_prefix')
                    ->label('Prefix')
                    ->copyable()
                    ->badge(),

                Tables\Columns\TextColumn::make('contact_email')
                    ->label('Email')
                    ->placeholder('-')
                    ->searchable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),

                Tables\Columns\TextColumn::make('last_used_at')
                    ->label('Terakhir Dipakai')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-')
                    ->sortable(),

                Tables\Columns\TextColumn::make('last_used_ip')
                    ->label('IP')
                    ->placeholder('-'),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\Action::make('regenerateKey')
                    ->label('Regenerate Key')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->action(function (ApiProvider $record): void {
                        $plainKey = ApiProvider::generatePlainKey();

                        $record->update([
                            'key_prefix' => ApiProvider::prefixFromPlainKey($plainKey),
                            'key_hash' => ApiProvider::hashPlainKey($plainKey),
                        ]);

                        Notification::make()
                            ->title('Provider key baru dibuat')
                            ->body("Simpan key ini sekarang. Key tidak akan ditampilkan lagi:\n\n{$plainKey}")
                            ->success()
                            ->persistent()
                            ->send();
                    }),

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
            'index' => Pages\ListApiProviders::route('/'),
            'create' => Pages\CreateApiProvider::route('/create'),
            'edit' => Pages\EditApiProvider::route('/{record}/edit'),
        ];
    }
}
