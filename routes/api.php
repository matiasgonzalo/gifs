<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GifController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [AuthController::class, 'login']);

Route::group([
    'prefix' => '/gifs',
    'middleware' => ['auth:api']
], function () {
    Route::get('/search', [GifController::class, 'search'])->name('gifs.search');
    Route::get('/get-by-id', [GifController::class, 'getById'])->name('gifs.getById');
    Route::post('/user/{user}', [GifController::class, 'store'])->name('gifs.save');
});
