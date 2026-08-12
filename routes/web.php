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
// 公開ページ。全書籍をページネーション（10件/ページ）で最新順に表示。
Route::get('/', [BookController::class, 'index'])
    ->name('books.index');

// 書籍登録
// 認証必須。全ジャンル一覧をチェックボックスで表示。
Route::get('/books/create', [BookController::class, 'create'])
    ->name('books.create');

// 書籍詳細
// 公開ページ。書籍詳細とレビュー・ジャンル・お気に入り・いいね機能を表示。
Route::get('/books/{book}', [BookController::class, 'show'])
    ->name('books.show');

// 書籍編集
// 認証+認可必須。作成者のみ閲覧可。
Route::get('/books/{book}/edit', [BookController::class, 'edit'])
    ->name('books.edit');

//書籍登録
Route::post('/books/store', [BookController::class, 'store'])
    ->name('books.store');

//書籍更新
Route::put('/books/{book}/update', [BookController::class, 'update'])
    ->name('books.update');

// ジャンル一覧
// 認証必須。各ジャンルの書籍数を表示。
Route::get('/genres', [GenreController::class, 'index'])
    ->name('genres.index');

// ジャンル登録
Route::get('/genres/create', [GenreController::class, 'create'])
    ->name('genres.create');

// ジャンル編集
Route::get('/genres/{genre}/edit', [GenreController::class, 'edit'])
    ->name('genres.edit');

// ジャンル削除
Route::delete('/genres/{genre}/destroy', [GenreController::class, 'destroy'])
    ->name('genres.destroy');


Route::post('/genres/store', [GenreController::class, 'store'])
    ->name('genres.store');

Route::put('/genres/{genre}/update', [GenreController::class, 'update'])
    ->name('genres.update');

// ジャンル詳細
// 認証必須。ジャンルに紐づく書籍をページネーション（10件/ページ）で表示。
Route::get('/genres/{genre}', [GenreController::class, 'show'])
    ->name('genres.show');

// レビュー編集
// 認証+認可必須。投稿者のみ閲覧可。
Route::get('/reviews/{review}/edit', [ReviewController::class, 'edit']);

Route::post('/reviews/{book}/store', [ReviewController::class, 'store'])->name('reviews.store');

Route::put('/reviews/{review}/update', [ReviewController::class, 'update'])->name('reviews.update');

Route::post('/reviews/{review}/like', [ReviewLikeController::class, 'toggle'])->name('reviews.like');


// お気に入り一覧
// 認証必須。ユーザーのお気に入り書籍をページネーション（10件/ページ）で表示。
Route::get('/favorites', [FavoriteController::class, 'index'])
    ->name('favorites.index');

Route::post('/favorites/{book}/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle');

// ランキング
// 公開ページ。レビュー平均評価TOP10を表示。
Route::get('/ranking', [RankingController::class, 'index'])
    ->name('ranking.index');

Route::middleware('auth')->group(function () {
});