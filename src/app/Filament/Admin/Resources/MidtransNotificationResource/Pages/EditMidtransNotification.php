<?php

namespace App\Filament\Admin\Resources\MidtransNotificationResource\Pages;

use App\Filament\Admin\Resources\MidtransNotificationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMidtransNotification extends EditRecord
{
    protected static string $resource = MidtransNotificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
