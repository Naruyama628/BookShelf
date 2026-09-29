<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\BookController;
use App\Http\Controllers\Api\V1\AuthController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });



Route::post('/v1/login', [AuthController::class, 'login']);

Route::prefix('v1')->group(function () {
    // 認証不要
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/books', [BookController::class, 'index']);
    Route::get('/books/{book}', [BookController::class, 'show']);

    // Sanctum認証必須
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/books', [BookController::class, 'store']);

        Route::put('/books/{book}', [BookController::class, 'update']);

        Route::post('/logout', [AuthController::class, 'logout']);
        
        Route::delete('/books/{book}', [BookController::class, 'destroy']);
    });
});