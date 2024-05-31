<?php

use Illuminate\Support\Facades\Route;
use Modules\Platform\Http\Controllers\AmenityController;
use Modules\Platform\Http\Controllers\BrewMethodController;
use Modules\Platform\Http\Controllers\CompanyController;
use Modules\Platform\Http\Controllers\DrinkOptionController;
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
    Route::get('/platform/brew-methods/{brewMethod}/edit', [BrewMethodController::class, 'edit'])
        ->name('platform.brew-methods.edit');
    Route::put('/platform/brew-methods/{brewMethod}', [BrewMethodController::class, 'update'])
        ->name('platform.brew-methods.update');

    Route::get('/platform/drink-options', [DrinkOptionController::class, 'index'])
        ->name('platform.drink-options.index');
    Route::get('/platform/drink-options/create', [DrinkOptionController::class, 'create'])
        ->name('platform.drink-options.create');
    Route::post('/platform/drink-options', [DrinkOptionController::class, 'store'])
        ->name('platform.drink-options.store');
    Route::get('/platform/drink-options/{drinkOption}/edit', [DrinkOptionController::class, 'edit'])
        ->name('platform.drink-options.edit');
    Route::put('/platform/drink-options/{drinkOption}', [DrinkOptionController::class, 'update'])
        ->name('platform.drink-options.update');

    Route::get('/platform/amenities', [AmenityController::class, 'index'])
        ->name('platform.amenities.index');
    Route::get('/platform/amenities/create', [AmenityController::class, 'create'])
        ->name('platform.amenities.create');
    Route::post('/platform/amenities', [AmenityController::class, 'store'])
        ->name('platform.amenities.store');
    Route::get('/platform/amenities/{amenity}/edit', [AmenityController::class, 'edit'])
        ->name('platform.amenities.edit');
    Route::put('/platform/amenities/{amenity}', [AmenityController::class, 'update'])
        ->name('platform.amenities.update');

    Route::get('/platform/companies', [CompanyController::class, 'index'])
        ->name('platform.companies.index');
    Route::get('/platform/companies/create', [CompanyController::class, 'create'])
        ->name('platform.companies.create');
    Route::post('/platform/companies', [CompanyController::class, 'store'])
        ->name('platform.companies.store');
    Route::get('/platform/companies/{company}', [CompanyController::class, 'show'])
        ->name('platform.companies.show');
    Route::get('/platform/companies/{company}/edit', [CompanyController::class, 'edit'])
        ->name('platform.companies.edit');
    Route::put('/platform/companies/{company}', [CompanyController::class, 'update'])
        ->name('platform.companies.update');
});
