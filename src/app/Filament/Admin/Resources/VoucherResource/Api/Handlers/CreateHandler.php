<?php
namespace App\Filament\Admin\Resources\VoucherResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Admin\Resources\VoucherResource;
use App\Filament\Admin\Resources\VoucherResource\Api\Requests\CreateVoucherRequest;

class CreateHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = VoucherResource::class;

    public static function getMethod()
    {
        return Handlers::POST;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }

    /**
     * Create Voucher
     *
     * @param CreateVoucherRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(CreateVoucherRequest $request)
    {
        $model = new (static::getModel());

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Create Resource");
    }
}