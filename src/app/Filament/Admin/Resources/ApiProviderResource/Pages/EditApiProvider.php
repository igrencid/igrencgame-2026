<?php

namespace App\Filament\Admin\Resources\ApiProviderResource\Pages;

use App\Filament\Admin\Resources\ApiProviderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditApiProvider extends EditRecord
{
    protected static string $resource = ApiProviderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
