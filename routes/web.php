<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FilterSubscriptionController;
use App\Http\Controllers\MapController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [DashboardController::class, 'index']);
Route::get('/map', [MapController::class, 'index']);
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// use Modules\Company\Models\Company;
// use Modules\Offering\Models\OfferingImportMap;
// use Modules\Offering\Http\Actions\Roasts\FetchShopifyProducts;

// Route::get('/test', function () {
//     $company = Company::find(5);
//     $importMap = OfferingImportMap::find(5);

//     $products = ( new FetchShopifyProducts($company, $importMap) )
//         ->execute();

//     foreach( $products as $product ){
//         echo $product['name'].' - '.$product['url'].'<br>';
//     }
// });

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__.'/auth.php';
