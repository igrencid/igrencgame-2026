<?php

namespace App\Filament\Admin\Resources\ApiProviderResource\Pages;

use App\Filament\Admin\Resources\ApiProviderResource;
use App\Models\ApiProvider;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateApiProvider extends CreateRecord
{
    protected static string $resource = ApiProviderResource::class;

    protected ?string $plainApiKey = null;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->plainApiKey = ApiProvider::generatePlainKey();

        $data['key_prefix'] = ApiProvider::prefixFromPlainKey($this->plainApiKey);
        $data['key_hash'] = ApiProvider::hashPlainKey($this->plainApiKey);

        return $data;
    }

    protected function afterCreate(): void
    {
        Notification::make()
            ->title('Provider key berhasil dibuat')
            ->body("Simpan key ini sekarang. Key tidak akan ditampilkan lagi:\n\n{$this->plainApiKey}")
            ->success()
            ->persistent()
            ->send();
    }
}
