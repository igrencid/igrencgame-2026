<?php

namespace App\Filament\Admin\Resources\GameInputFieldResource\Pages;

use App\Filament\Admin\Resources\GameInputFieldResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGameInputField extends EditRecord
{
    protected static string $resource = GameInputFieldResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
