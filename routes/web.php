<?php

use App\Http\Controllers\ApiDocsController;
use App\Http\Controllers\ChangelogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FilterSubscriptionController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\OgImageController;
use App\Http\Controllers\PersonalAccessTokenController;
use App\Http\Controllers\PrivacyPolicyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TermsOfServiceController;
use App\Http\Controllers\TransparencyController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [DashboardController::class, 'index']);
Route::get('/map', [MapController::class, 'index']);
Route::get('/changelog', [ChangelogController::class, 'index'])->name('changelog.index');
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('/transparency', [TransparencyController::class, 'index'])->name('transparency.index');
Route::get('/api-docs', [ApiDocsController::class, 'index'])->name('api-docs.index');
Route::get('/terms', [TermsOfServiceController::class, 'index'])->name('terms.index');
Route::get('/privacy', [PrivacyPolicyController::class, 'index'])->name('privacy.index');

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

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/profile-picture', [ProfileController::class, 'updateProfilePicture'])->name('profile.picture.update');
    Route::delete('/profile/profile-picture', [ProfileController::class, 'removeProfilePicture'])->name('profile.picture.remove');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Filter subscriptions
    Route::post('/subscriptions', [FilterSubscriptionController::class, 'store'])->name('subscriptions.store');
    Route::get('/subscriptions', [FilterSubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::delete('/subscriptions/{subscription}', [FilterSubscriptionController::class, 'destroy'])->name('subscriptions.destroy');

    // Personal access tokens
    Route::post('/profile/tokens', [PersonalAccessTokenController::class, 'store'])->name('tokens.store');
    Route::delete('/profile/tokens/{tokenId}', [PersonalAccessTokenController::class, 'destroy'])->name('tokens.destroy');
});

require __DIR__.'/auth.php';
