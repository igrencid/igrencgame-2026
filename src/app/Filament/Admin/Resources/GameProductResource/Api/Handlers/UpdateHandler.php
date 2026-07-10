<?php
namespace App\Filament\Admin\Resources\GameProductResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Admin\Resources\GameProductResource;
use App\Filament\Admin\Resources\GameProductResource\Api\Requests\UpdateGameProductRequest;

class UpdateHandler extends Handlers {
    public static string | null $uri = '/{id}';
    public static string | null $resource = GameProductResource::class;

    public static function getMethod()
    {
        return Handlers::PUT;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }


    /**
     * Update GameProduct
     *
     * @param UpdateGameProductRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(UpdateGameProductRequest $request)
    {
        $id = $request->route('id');

        $model = static::getModel()::find($id);

        if (!$model) return static::sendNotFoundResponse();

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Update Resource");
    }
}