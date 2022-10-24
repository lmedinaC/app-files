<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('files')->middleware(['jwt.verify','throttle:3,1'])->name('files.')->group(function () {
    Route::get('/',[FileController::class, 'index'])->name('index');
    Route::post('/',[FileController::class, 'store'])->name('store');
    Route::delete('/soft-destroy/{id}',[FileController::class, 'softDestroy'])->name('soft-destroy');
    Route::delete('/hard-destroy/{id}',[FileController::class, 'hardDestroy'])->name('hard-destroy');
    Route::post('/dispatch',[FileController::class, 'multipleStore'])->name('dispatch');
    
});


Route::post('/credentials',[AuthController::class, 'authenticate'])->name('authenticate');