<?php

use Illuminate\Support\Facades\Route;
use Modules\Platform\Http\Controllers\BrewMethodController;
use Modules\Platform\Http\Controllers\PlatformController;
use Modules\Platform\Http\Middleware\CanManagePlatform;

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

Route::middleware([CanManagePlatform::class])->group(function () {
    Route::get('/platform', [PlatformController::class, 'index'])
        ->name('platform.index');

    Route::get('/platform/brew-methods', [BrewMethodController::class, 'index'])
        ->name('platform.brew-methods.index');
    Route::get('/platform/brew-methods/create', [BrewMethodController::class, 'create'])
        ->name('platform.brew-methods.create');
    Route::post('/platform/brew-methods', [BrewMethodController::class, 'store'])
        ->name('platform.brew-methods.store');
});
