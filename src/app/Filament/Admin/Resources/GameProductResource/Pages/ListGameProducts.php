<?php

namespace App\Filament\Admin\Resources\GameProductResource\Pages;

use App\Filament\Admin\Resources\GameProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGameProducts extends ListRecords
{
    protected static string $resource = GameProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
