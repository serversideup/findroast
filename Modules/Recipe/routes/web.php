<?php

use Illuminate\Support\Facades\Route;
use Modules\Recipe\Http\Controllers\RecipeController;

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

Route::prefix('recipes')->group(function () {
    Route::get('/', [RecipeController::class, 'index'])->name('recipes.index');
    Route::get('/create', [RecipeController::class, 'create'])->middleware('auth')->name('recipes.create');
    Route::post('/', [RecipeController::class, 'store'])->middleware('auth')->name('recipes.store');

    Route::middleware('auth')->group(function () {
        Route::get('/saved', [RecipeController::class, 'saved'])->name('recipes.saved');
        Route::get('/my-recipes', [RecipeController::class, 'myRecipes'])->name('recipes.my-recipes');
    });

    Route::get('/{slug}', [RecipeController::class, 'show'])->name('recipes.show');
    Route::get('/{slug}/edit', [RecipeController::class, 'edit'])->middleware('auth')->name('recipes.edit');
    Route::put('/{slug}', [RecipeController::class, 'update'])->middleware('auth')->name('recipes.update');
    Route::delete('/{slug}', [RecipeController::class, 'destroy'])->middleware('auth')->name('recipes.destroy');
    Route::post('/{slug}/toggle-save', [RecipeController::class, 'toggleSave'])->middleware('auth')->name('recipes.toggle-save');
});
