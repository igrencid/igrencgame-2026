<?php
namespace App\Filament\Admin\Resources\GameResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Admin\Resources\GameResource;
use App\Filament\Admin\Resources\GameResource\Api\Requests\CreateGameRequest;

class CreateHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = GameResource::class;

    public static function getMethod()
    {
        return Handlers::POST;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }

    /**
     * Create Game
     *
     * @param CreateGameRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(CreateGameRequest $request)
    {
        $model = new (static::getModel());

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Create Resource");
    }
}