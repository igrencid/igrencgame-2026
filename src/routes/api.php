<?php

use App\Filament\Admin\Resources\CustomerResource\Api\CustomerApiService;
use App\Filament\Admin\Resources\GameProductResource\Api\GameProductApiService;
use App\Filament\Admin\Resources\GameResource\Api\GameApiService;
use App\Filament\Admin\Resources\OrderResource\Api\OrderApiService;
use App\Filament\Admin\Resources\VoucherResource\Api\VoucherApiService;
use App\Http\Controllers\Api\Provider\GameController;
use App\Http\Controllers\Api\Provider\OrderController;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Pemeriksaan Provider API
|--------------------------------------------------------------------------
*/
Route::middleware('provider.key')
    ->get('/provider/ping', function (Request $request) {
        $provider = $request->attributes->get('api_provider');

        return response()->json([
            'message' => 'Provider key valid.',
            'provider' => [
                'id' => $provider->id,
                'name' => $provider->name,
                'key_prefix' => $provider->key_prefix,
            ],
        ]);
    })
    ->name('api.provider.ping');

/*
|--------------------------------------------------------------------------
| API Katalog Provider
|--------------------------------------------------------------------------
*/
Route::middleware('provider.key:games:read')->group(function () {
    Route::get('/games', [GameController::class, 'index'])
        ->name('api.games.index');

    Route::get('/games/{slug}', [GameController::class, 'show'])
        ->name('api.games.show');

    Route::get('/games/{slug}/products', [GameController::class, 'products'])
        ->name('api.games.products');

    Route::get('/games/{slug}/input-fields', [GameController::class, 'inputFields'])
        ->name('api.games.input-fields');
});

/*
|--------------------------------------------------------------------------
| API Pesanan Provider
|--------------------------------------------------------------------------
*/
Route::middleware('provider.key:orders:create')
    ->post('/orders', [OrderController::class, 'store'])
    ->name('api.orders.store');

Route::middleware('provider.key:orders:read')
    ->get('/orders/{invoice}', [OrderController::class, 'show'])
    ->name('api.orders.show');

/*
|--------------------------------------------------------------------------
| API Administrasi Filament
|--------------------------------------------------------------------------
| Package rupadana/filament-api-service tidak memuat route resource secara
| otomatis pada proyek ini. Route didaftarkan secara eksplisit agar tetap
| tersedia pada route cache dan proses Artisan yang berbeda.
*/
Route::prefix('admin')
    ->middleware([
        'auth:sanctum',
    ])
    ->group(function (): void {
        $panel = Filament::getPanel('admin');

        GameApiService::registerRoutes($panel);
        GameProductApiService::registerRoutes($panel);
        VoucherApiService::registerRoutes($panel);
        CustomerApiService::registerRoutes($panel);
        OrderApiService::registerRoutes($panel);
    });
