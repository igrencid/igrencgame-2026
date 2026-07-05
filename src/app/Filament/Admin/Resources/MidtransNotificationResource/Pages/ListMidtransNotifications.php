<?php

namespace App\Filament\Admin\Resources\MidtransNotificationResource\Pages;

use App\Filament\Admin\Resources\MidtransNotificationResource;
use Filament\Resources\Pages\ListRecords;

class ListMidtransNotifications extends ListRecords
{
    protected static string $resource = MidtransNotificationResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}