<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ReviewLikeController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\RankingController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


// 書籍一覧（トップ）
// GET /
Route::get('/', [BookController::class, 'index'])
    ->name('books.index');

// ==================================================
// 書籍
// ==================================================
Route::prefix('books')->group(function () {

    Route::middleware('auth')->group(function () {

        // 書籍登録画面
        // GET /books/create
        Route::get('/create', [BookController::class, 'create'])
            ->name('books.create');

        // 書籍登録
        // POST /books
        Route::post('/', [BookController::class, 'store'])
            ->name('books.store');

        // 書籍編集画面
        // GET /books/{book}/edit
        // 認可
        Route::get('/{book}/edit', [BookController::class, 'edit'])
            ->name('books.edit');

        // 書籍更新
        // PUT /books/{book}
        // 認可
        Route::put('/{book}', [BookController::class, 'update'])
            ->name('books.update');

        // 書籍削除
        // DELETE /books/{book}
        // 認可
        Route::delete('/{book}', [BookController::class, 'destroy'])
            ->name('books.destroy');

        // レビュー投稿
        // POST /books/{book}/reviews
        Route::post('/{book}/reviews', [ReviewController::class, 'store'])
            ->name('reviews.store');

        // お気に入りトグル
        // POST /books/{book}/favorites
        Route::post('/{book}/favorites', [FavoriteController::class, 'toggle'])
            ->name('favorites.toggle');
    });

    // 書籍詳細
    // GET /books/{book}
    // ゲストアクセス可能
    Route::get('/{book}', [BookController::class, 'show'])
        ->name('books.show');
});


// ==================================================
// レビュー
// ==================================================
Route::prefix('reviews')->middleware('auth')->group(function () {

    // レビュー編集画面
    // GET /reviews/{review}/edit
    // 認可
    Route::get('/{review}/edit', [ReviewController::class, 'edit'])
        ->name('reviews.edit');

    // レビュー更新
    // PUT /reviews/{review}
    // 認可
    Route::put('/{review}', [ReviewController::class, 'update'])
        ->name('reviews.update');

    // レビュー削除
    // DELETE /reviews/{review}
    Route::delete('/{review}', [ReviewController::class, 'destroy'])
        ->name('reviews.destroy');

    // レビューいいね
    // POST /reviews/{review}/like
    Route::post('/{review}/like', [ReviewLikeController::class, 'toggle'])
        ->name('reviews.like');
});


// ==================================================
// お気に入り
// ==================================================
Route::prefix('favorites')->middleware('auth')->group(function () {

    // お気に入り一覧
    // GET /favorites
    Route::get('/', [FavoriteController::class, 'index'])
        ->name('favorites.index');
});


// ==================================================
// ジャンル
// ==================================================
Route::prefix('genres')->middleware('auth')->group(function () {

    // ジャンル一覧
    // GET /genres
    Route::get('/', [GenreController::class, 'index'])
        ->name('genres.index');

    // ジャンル登録画面
    // GET /genres/create
    Route::get('/create', [GenreController::class, 'create'])
        ->name('genres.create');

    // ジャンル登録
    // POST /genres
    Route::post('/', [GenreController::class, 'store'])
        ->name('genres.store');

    // ジャンル編集画面
    // GET /genres/{genre}/edit
    Route::get('/{genre}/edit', [GenreController::class, 'edit'])
        ->name('genres.edit');

    // ジャンル更新
    // PUT /genres/{genre}
    Route::put('/{genre}', [GenreController::class, 'update'])
        ->name('genres.update');

    // ジャンル削除
    // DELETE /genres/{genre}
    Route::delete('/{genre}', [GenreController::class, 'destroy'])
        ->name('genres.destroy');

    // ジャンル詳細
    // GET /genres/{genre}
    Route::get('/{genre}', [GenreController::class, 'show'])
        ->name('genres.show');
});


// ==================================================
// ランキング
// ==================================================

// GET /ranking
// ゲストアクセス可能
Route::get('/ranking', [RankingController::class, 'index'])
    ->name('ranking.index');