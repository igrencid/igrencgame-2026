<?php

namespace App\Filament\Admin\Resources\VoucherResource\Api;

use App\Filament\Admin\Resources\VoucherResource;
use Rupadana\ApiService\ApiService;

class VoucherApiService extends ApiService
{
    protected static string|null $resource = VoucherResource::class;

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
