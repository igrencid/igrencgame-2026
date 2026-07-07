<?php

use App\Http\Controllers\Api\Provider\GameController;
use App\Http\Controllers\Api\Provider\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('provider.key')->get('/provider/ping', function (Request $request) {
    $provider = $request->attributes->get('api_provider');

    return response()->json([
        'message' => 'Provider key valid.',
        'provider' => [
            'id' => $provider->id,
            'name' => $provider->name,
            'key_prefix' => $provider->key_prefix,
        ],
    ]);
});

Route::middleware('provider.key:games:read')->group(function () {
    Route::get('/games', [GameController::class, 'index']);
    Route::get('/games/{slug}', [GameController::class, 'show']);
    Route::get('/games/{slug}/products', [GameController::class, 'products']);
    Route::get('/games/{slug}/input-fields', [GameController::class, 'inputFields']);
});

Route::middleware('provider.key:orders:create')->post('/orders', [OrderController::class, 'store']);
Route::middleware('provider.key:orders:read')->get('/orders/{invoice}', [OrderController::class, 'show']);
