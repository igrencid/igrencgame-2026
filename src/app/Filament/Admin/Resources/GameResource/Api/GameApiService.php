<?php

namespace App\Filament\Admin\Resources\GameResource\Api;

use App\Filament\Admin\Resources\GameResource;
use Rupadana\ApiService\ApiService;

class GameApiService extends ApiService
{
    protected static string|null $resource = GameResource::class;

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
