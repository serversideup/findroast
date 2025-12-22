<?php

use Illuminate\Support\Facades\Route;
use Modules\Offering\Http\Controllers\OfferingController;
use Modules\Offering\Http\Controllers\SubscriptionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/offerings', [OfferingController::class, 'index'])
    ->name('offerings.index');

Route::post('/subscriptions', [SubscriptionController::class, 'store'])
    ->name('subscriptions.store');

Route::get('/subscriptions/verify/{token}', [SubscriptionController::class, 'verify'])
    ->name('subscriptions.verify');

Route::delete('/subscriptions/{subscription}', [SubscriptionController::class, 'unsubscribe'])
    ->name('subscriptions.unsubscribe');