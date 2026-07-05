<?php

use App\Livewire\Customer\CheckoutPage;
use App\Livewire\Customer\GameDetailPage;
use App\Livewire\Customer\HomePage;
use App\Livewire\Customer\OrderStatusPage;
use App\Livewire\Customer\PaymentPage;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

/* NOTE: Do Not Remove
/ Livewire asset handling if using sub folder in domain
*/

Livewire::setUpdateRoute(function ($handle) {
    return Route::post(config('app.asset_prefix') . '/livewire/update', $handle);
});

Livewire::setScriptRoute(function ($handle) {
    return Route::get(config('app.asset_prefix') . '/livewire/livewire.js', $handle);
});
/*
/ END
*/

Route::get('/', HomePage::class)->name('home');

Route::get('/games/{slug}', GameDetailPage::class)->name('games.show');
Route::get('/checkout/{slug}', CheckoutPage::class)->name('checkout.show');
Route::get('/payment/{invoice}', PaymentPage::class)->name('payment.show');
Route::get('/orders/{invoice}', OrderStatusPage::class)->name('orders.show');