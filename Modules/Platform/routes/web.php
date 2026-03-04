<?php

use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;
use Modules\Platform\Http\Controllers\AmenityController;
use Modules\Platform\Http\Controllers\BrewMethodController;
use Modules\Platform\Http\Controllers\CafesController;
use Modules\Platform\Http\Controllers\CompanyController;
use Modules\Platform\Http\Controllers\CountryController;
use Modules\Platform\Http\Controllers\DrinkOptionController;
use Modules\Platform\Http\Controllers\ElevationController;
use Modules\Platform\Http\Controllers\FlavorNoteController;
use Modules\Platform\Http\Controllers\OfferingsController;
use Modules\Platform\Http\Controllers\PlatformController;
use Modules\Platform\Http\Controllers\ProcessController;
use Modules\Platform\Http\Controllers\RoastController;
use Modules\Platform\Http\Controllers\UsersController;
use Modules\Platform\Http\Controllers\VarietyController;
use Modules\Platform\Http\Controllers\ChangelogController;
use Modules\Platform\Http\Controllers\MessagesController;
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

Route::middleware([Authenticate::class, CanManagePlatform::class])->group(function () {
    Route::get('/platform', [PlatformController::class, 'index'])
        ->name('platform.index');

    Route::get('/platform/brew-methods', [BrewMethodController::class, 'index'])
        ->name('platform.brew-methods.index');
    Route::post('/platform/brew-methods', [BrewMethodController::class, 'store'])
        ->name('platform.brew-methods.store');
    Route::put('/platform/brew-methods/{brewMethod}', [BrewMethodController::class, 'update'])
        ->name('platform.brew-methods.update');

    Route::get('/platform/drink-options', [DrinkOptionController::class, 'index'])
        ->name('platform.drink-options.index');
    Route::post('/platform/drink-options', [DrinkOptionController::class, 'store'])
        ->name('platform.drink-options.store');
    Route::put('/platform/drink-options/{drinkOption}', [DrinkOptionController::class, 'update'])
        ->name('platform.drink-options.update');

    Route::get('/platform/amenities', [AmenityController::class, 'index'])
        ->name('platform.amenities.index');
    Route::post('/platform/amenities', [AmenityController::class, 'store'])
        ->name('platform.amenities.store');
    Route::put('/platform/amenities/{amenity}', [AmenityController::class, 'update'])
        ->name('platform.amenities.update');

    Route::get('/platform/companies', [CompanyController::class, 'index'])
        ->name('platform.companies.index');
    Route::post('/platform/companies', [CompanyController::class, 'store'])
        ->name('platform.companies.store');
    Route::put('/platform/companies/{company}', [CompanyController::class, 'update'])
        ->name('platform.companies.update');

    Route::put('/platform/companies/{company}/offerings/sync', [OfferingsController::class, 'sync'])
        ->name('platform.companies.offerings.sync');

    Route::get('/platform/cafes', [CafesController::class, 'index'])
        ->name('platform.cafes.index');
    Route::get('/platform/cafes/create', [CafesController::class, 'create'])
        ->name('platform.cafes.create');
    Route::post('/platform/cafes', [CafesController::class, 'store'])
        ->name('platform.cafes.store');
    Route::get('/platform/cafes/{cafe}', [CafesController::class, 'show'])
        ->name('platform.cafes.show');
    Route::get('/platform/cafes/{cafe}/edit', [CafesController::class, 'edit'])
        ->name('platform.cafes.edit');
    Route::put('/platform/cafes/{cafe}', [CafesController::class, 'update'])
        ->name('platform.cafes.update');
    Route::delete('/platform/cafes/{cafe}', [CafesController::class, 'destroy'])
        ->name('platform.cafes.destroy');

    Route::get('/platform/countries', [CountryController::class, 'index'])
        ->name('platform.countries.index');
    Route::get('/platform/countries/{country}/edit', [CountryController::class, 'edit'])
        ->name('platform.countries.edit');
    Route::put('/platform/countries/{country}', [CountryController::class, 'update'])
        ->name('platform.countries.update');
    Route::delete('/platform/countries/{country}', [CountryController::class, 'delete'])
        ->name('platform.countries.delete');

    Route::get('/platform/processes', [ProcessController::class, 'index'])
        ->name('platform.processes.index');
    Route::put('/platform/processes/{process}', [ProcessController::class, 'update'])
        ->name('platform.processes.update');
    Route::post('/platform/processes/{process}/migrate', [ProcessController::class, 'migrate'])
        ->name('platform.processes.migrate');
    Route::delete('/platform/processes/{process}', [ProcessController::class, 'delete'])
        ->name('platform.processes.delete');

    Route::get('/platform/elevations', [ElevationController::class, 'index'])
        ->name('platform.elevations.index');
    Route::put('/platform/elevations/{elevation}', [ElevationController::class, 'update'])
        ->name('platform.elevations.update');
    Route::delete('/platform/elevations/{elevation}', [ElevationController::class, 'delete'])
        ->name('platform.elevations.delete');

    Route::get('/platform/flavor-notes', [FlavorNoteController::class, 'index'])
        ->name('platform.flavor-notes.index');
    Route::put('/platform/flavor-notes/{flavorNote}', [FlavorNoteController::class, 'update'])
        ->name('platform.flavor-notes.update');
    Route::post('/platform/flavor-notes/{flavorNote}/migrate', [FlavorNoteController::class, 'migrate'])
        ->name('platform.flavor-notes.migrate');
    Route::delete('/platform/flavor-notes/{flavorNote}', [FlavorNoteController::class, 'delete'])
        ->name('platform.flavor-notes.delete');

    Route::get('/platform/varieties', [VarietyController::class, 'index'])
        ->name('platform.varieties.index');
    Route::put('/platform/varieties/{variety}', [VarietyController::class, 'update'])
        ->name('platform.varieties.update');
    Route::post('/platform/varieties/{variety}/migrate', [VarietyController::class, 'migrate'])
        ->name('platform.varieties.migrate');
    Route::delete('/platform/varieties/{variety}', [VarietyController::class, 'delete'])
        ->name('platform.varieties.delete');

    Route::get('/platform/roasts', [RoastController::class, 'index'])
        ->name('platform.roasts.index');
    Route::put('/platform/roasts/{roast}', [RoastController::class, 'update'])
        ->name('platform.roasts.update');
    Route::delete('/platform/roasts/{roast}', [RoastController::class, 'delete'])
        ->name('platform.roasts.delete');

    Route::post('/platform/offerings/preview', [OfferingsController::class, 'preview'])
        ->name('platform.offerings.preview');

    Route::post('/platform/offerings/mark-invalid', [OfferingsController::class, 'markInvalid'])
        ->name('platform.offerings.mark-invalid');

    Route::get('/platform/messages', [MessagesController::class, 'index'])
        ->name('platform.messages.index');
    Route::put('/platform/messages/{message}', [MessagesController::class, 'update'])
        ->name('platform.messages.update');
    Route::delete('/platform/messages/{message}', [MessagesController::class, 'destroy'])
        ->name('platform.messages.destroy');

    Route::get('/platform/users', [UsersController::class, 'index'])
        ->name('platform.users.index');

    Route::get('/platform/changelog', [ChangelogController::class, 'index'])
        ->name('platform.changelog.index');
    Route::get('/platform/changelog/create', [ChangelogController::class, 'create'])
        ->name('platform.changelog.create');
    Route::post('/platform/changelog', [ChangelogController::class, 'store'])
        ->name('platform.changelog.store');
    Route::get('/platform/changelog/{changelog}/edit', [ChangelogController::class, 'edit'])
        ->name('platform.changelog.edit');
    Route::put('/platform/changelog/{changelog}', [ChangelogController::class, 'update'])
        ->name('platform.changelog.update');
    Route::put('/platform/changelog/{changelog}/publish', [ChangelogController::class, 'publish'])
        ->name('platform.changelog.publish');
    Route::delete('/platform/changelog/{changelog}', [ChangelogController::class, 'destroy'])
        ->name('platform.changelog.destroy');
});