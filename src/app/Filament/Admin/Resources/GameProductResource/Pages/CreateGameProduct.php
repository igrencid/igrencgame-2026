<?php

namespace App\Filament\Admin\Resources\GameProductResource\Pages;

use App\Filament\Admin\Resources\GameProductResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateGameProduct extends CreateRecord
{
    protected static string $resource = GameProductResource::class;
}
