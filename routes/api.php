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
    'middleware' => ['api']
], function () {
    Route::get('/search', [GifController::class, 'search']);
    Route::get('/get-by-id', [GifController::class, 'getById']);
    Route::post('/user/{id}', [GifController::class, 'store']);
});
