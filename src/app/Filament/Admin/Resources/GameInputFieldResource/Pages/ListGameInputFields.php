<?php

namespace App\Filament\Admin\Resources\GameInputFieldResource\Pages;

use App\Filament\Admin\Resources\GameInputFieldResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGameInputFields extends ListRecords
{
    protected static string $resource = GameInputFieldResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
