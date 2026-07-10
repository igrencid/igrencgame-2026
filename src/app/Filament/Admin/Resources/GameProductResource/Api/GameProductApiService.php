<?php

namespace App\Filament\Admin\Resources\GameProductResource\Api;

use App\Filament\Admin\Resources\GameProductResource;
use Rupadana\ApiService\ApiService;

class GameProductApiService extends ApiService
{
    protected static string|null $resource = GameProductResource::class;

    public static function handlers(): array
    {
        return [
            Handlers\CreateHandler::class,
            Handlers\UpdateHandler::class,
            Handlers\DeleteHandler::class,
            Handlers\PaginationHandler::class,
            Handlers\DetailHandler::class,
        ];
    }
}
