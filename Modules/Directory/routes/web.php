<?php

use Illuminate\Support\Facades\Route;
use Modules\Directory\Http\Controllers\DirectoryController;

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

use Illuminate\Support\Facades\Http;

Route::group([], function () {
    Route::resource('directory', DirectoryController::class)->names('directory');
});
