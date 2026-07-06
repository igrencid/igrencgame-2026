<?php

namespace App\Filament\Admin\Resources\ContentPageResource\Pages;

use App\Filament\Admin\Resources\ContentPageResource;
use Filament\Resources\Pages\EditRecord;

class EditContentPage extends EditRecord
{
    protected static string $resource = ContentPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\DeleteAction::make(),
        ];
    }
}
