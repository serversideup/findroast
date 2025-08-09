<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [DashboardController::class, 'index']);

use Modules\Offering\Http\Actions\Roasts\UniversalCoffeeScraper;

Route::get('/scrape', function(){
    $scraper = new UniversalCoffeeScraper();
    

    $collectionData = $scraper->scrapeCollection('https://www.blackwhiteroasters.com/collections/all-coffee');

    echo '<pre>';
    print_r($collectionData);
    echo '</pre>';
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__.'/auth.php';
