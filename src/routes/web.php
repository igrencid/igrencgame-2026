<?php

use App\Http\Controllers\Customer\MidtransFinishController;
use App\Livewire\Customer\CheckoutPage;
use App\Livewire\Customer\ContentPageShow;
use App\Livewire\Customer\FaqPage;
use App\Livewire\Customer\GameDetailPage;
use App\Livewire\Customer\HomePage;
use App\Livewire\Customer\OrderStatusPage;
use App\Livewire\Customer\PaymentPage;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use App\Http\Controllers\Customer\MidtransNotificationController;

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

/*
|--------------------------------------------------------------------------
| Public Content Pages
|--------------------------------------------------------------------------
*/
Route::get('/faq', FaqPage::class)->name('faq.index');
Route::get('/terms', ContentPageShow::class)->defaults('slug', 'terms')->name('terms.show');
Route::get('/privacy', ContentPageShow::class)->defaults('slug', 'privacy')->name('privacy.show');

/*
|--------------------------------------------------------------------------
| Midtrans Finish Redirect
|--------------------------------------------------------------------------
| Harus di atas /payment/{invoice}, biar "finish" tidak kebaca sebagai invoice.
*/
Route::get('/payment/finish', MidtransFinishController::class)->name('payment.finish');

Route::get('/payment/{invoice}', PaymentPage::class)->name('payment.show');
Route::get('/orders/{invoice}', OrderStatusPage::class)->name('orders.show');

Route::post('/midtrans/notification', MidtransNotificationController::class)
    ->name('midtrans.notification');
Route::get('/register', \App\Livewire\Customer\Auth\RegisterPage::class)
    ->middleware('guest:customer')
    ->name('customer.register');

Route::get('/login', \App\Livewire\Customer\Auth\LoginPage::class)
    ->middleware('guest:customer')
    ->name('customer.login');

Route::post('/logout', function () {
    \Illuminate\Support\Facades\Auth::guard('customer')->logout();

    request()->session()->regenerateToken();

    return redirect()->route('home');
})->name('customer.logout');

Route::get('/account', \App\Livewire\Customer\Account\DashboardPage::class)
    ->middleware('customer.auth')
    ->name('customer.account');

Route::get('/account/orders', \App\Livewire\Customer\Account\OrdersPage::class)
    ->middleware('customer.auth')
    ->name('customer.orders');
