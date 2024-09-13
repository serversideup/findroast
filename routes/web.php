<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use Modules\Offering\Http\Actions\Roasts\ExtractImageData;
use Modules\Offering\Jobs\SyncRoast;
use Modules\Offering\Models\Roast;
use Illuminate\Support\Facades\Http;

Route::get('/test', function () {
    // Testing St. Frank. Need to build a page that
    // shows all new coffees for the day and their attributes
    // so we can test what comes in.
        $roast = Roast::find(135);

        SyncRoast::dispatch($roast);
});

Route::get('/', [DashboardController::class, 'index']);

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
