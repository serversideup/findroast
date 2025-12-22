<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [DashboardController::class, 'index']);

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
