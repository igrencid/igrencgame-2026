<?php

namespace App\Filament\Admin\Resources\FaqItemResource\Pages;

use App\Filament\Admin\Resources\FaqItemResource;
use Filament\Resources\Pages\ListRecords;

class ListFaqItems extends ListRecords
{
    protected static string $resource = FaqItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make(),
        ];
    }
}
