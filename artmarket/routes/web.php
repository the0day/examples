<?php

use App\Http\Controllers\Account\OrderController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OfferController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/category/{alias}', [HomeController::class, 'index'])->name('category');
Route::get('/profile/{user}', 'ProfileController@view')->name('user.profile');

Route::middleware(['auth'])->group(function () {
    Route::get('/account/edit', 'Account\PersonalController@edit')->name('account.personal');
    Route::post('/account/edit', 'Account\PersonalController@update')->name('account.personal.update');
    Route::get('/account/offers', 'Account\OfferController@list')->name('account.offers.list');
    Route::get('/account/offers/{offerType}/create', 'Account\OfferController@create')->name('account.offers.create');
    Route::post('/account/offers/create', 'Account\OfferController@store')->name('account.offers.store');
    Route::get('/account/offers/edit/{offer:id}', 'Account\OfferController@edit')->name('account.offers.edit');
    Route::get('/account/payments', 'AccountController@payments')->name('account.payments');
    Route::get('/account/withdraw', 'AccountController@withdraw')->name('account.withdraw');
    Route::get('/account/reviews', 'AccountController@reviews')->name('account.reviews');

    Route::get('/account/purchases', [OrderController::class, 'purchases'])->name('account.purchases');
    Route::get('/account/orders', [OrderController::class, 'index'])->name('account.orders');
    Route::get('/account/orders/{order}', [OrderController::class, 'view'])->name('account.orders.view');
    Route::post('/account/orders/{order}/action', [OrderController::class, 'action'])->name('account.orders.action');
    Route::post('/account/orders/{order}/send', [OrderController::class, 'sendMessage'])->name('account.orders.sendMessage');
    Route::post('/account/orders/{order}/upload', [OrderController::class, 'uploadWork'])->name('account.orders.upload');

    Route::post('/{user}/{offer}/checkout', [OfferController::class, 'order'])->name('offer.checkout');
    Route::get('/{user}/{offer}/checkout', [OfferController::class, 'checkout'])->name('offer.checkout.view');
    Route::post('/{user}/{offer}/payment', [OfferController::class, 'payment'])->name('offer.checkout.payment');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__ . '/auth.php';

Route::get('/{user}/{offer}', [OfferController::class, 'view'])->name('offer.view');

