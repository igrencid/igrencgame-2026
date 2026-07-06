<?php

namespace App\Filament\Admin\Resources\FaqItemResource\Pages;

use App\Filament\Admin\Resources\FaqItemResource;
use Filament\Resources\Pages\EditRecord;

class EditFaqItem extends EditRecord
{
    protected static string $resource = FaqItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\DeleteAction::make(),
        ];
    }
}
