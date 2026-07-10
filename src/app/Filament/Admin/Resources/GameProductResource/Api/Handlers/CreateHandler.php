<?php
namespace App\Filament\Admin\Resources\GameProductResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Admin\Resources\GameProductResource;
use App\Filament\Admin\Resources\GameProductResource\Api\Requests\CreateGameProductRequest;

class CreateHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = GameProductResource::class;

    public static function getMethod()
    {
        return Handlers::POST;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }

    /**
     * Create GameProduct
     *
     * @param CreateGameProductRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(CreateGameProductRequest $request)
    {
        $model = new (static::getModel());

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Create Resource");
    }
}