<?php

use Illuminate\Support\Facades\Route;
use Modules\Offering\Http\Controllers\Api\RoastController;

/*
 *--------------------------------------------------------------------------
 * API Routes
 *--------------------------------------------------------------------------
 *
 * Here is where you can register API routes for your application. These
 * routes are loaded by the RouteServiceProvider within a group which
 * is assigned the "api" middleware group. Enjoy building your API!
 *
*/

Route::middleware(['auth:sanctum', 'throttle:60,1'])->prefix('v1')->group(function () {
    Route::get('/roasts', [RoastController::class, 'index'])->name('roasts.index');
    Route::get('/roasts/{roast}', [RoastController::class, 'show'])->name('roasts.show');
});
