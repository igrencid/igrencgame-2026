<?php

namespace App\Filament\Admin\Resources\GameResource\Api\Handlers;

use App\Filament\Admin\Resources\GameResource;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use App\Filament\Admin\Resources\GameResource\Api\Transformers\GameTransformer;

class DetailHandler extends Handlers
{
    public static string | null $uri = '/{id}';
    public static string | null $resource = GameResource::class;


    /**
     * Show Game
     *
     * @param Request $request
     * @return GameTransformer
     */
    public function handler(Request $request)
    {
        $id = $request->route('id');
        
        $query = static::getEloquentQuery();

        $query = QueryBuilder::for(
            $query->where(static::getKeyName(), $id)
        )
            ->first();

        if (!$query) return static::sendNotFoundResponse();

        return new GameTransformer($query);
    }
}
