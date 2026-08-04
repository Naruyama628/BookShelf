<?php

use Illuminate\Support\Facades\Route;

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
Route::get('/', function () {
    return view('auth/register');
});

// 書籍詳細
// 公開ページ。書籍詳細とレビュー・ジャンル・お気に入り・いいね機能を表示。
Route::get('/books/{book}', function () {
    return view('auth.');
});

// 書籍登録
// 認証必須。全ジャンル一覧をチェックボックスで表示。
Route::get('/books/create', function () {
    return view('auth.');
});

// 書籍編集
// 認証+認可必須。作成者のみ閲覧可。
Route::get('/books/{book}/edit', function () {
    return view('auth.');
});

// ジャンル一覧
// 認証必須。各ジャンルの書籍数を表示。
Route::get('/genres', function () {
    return view('auth.');
});

// ジャンル詳細
// 認証必須。ジャンルに紐づく書籍をページネーション（10件/ページ）で表示。
Route::get('/genres/{genre}', function () {
    return view('auth.');
});

// ジャンル登録
Route::get('/genres/create', function () {
    return view('auth.');
});

// ジャンル編集
Route::get('/genres/{genre}/edit', function () {
    return view('auth.');
});

// レビュー編集
// 認証+認可必須。投稿者のみ閲覧可。
Route::get('/reviews/{review}/edit', function () {
    return view('auth.');
});

// お気に入り一覧
// 認証必須。ユーザーのお気に入り書籍をページネーション（10件/ページ）で表示。
Route::get('/favorites', function () {
    return view('auth.');
});

// ランキング
// 公開ページ。レビュー平均評価TOP10を表示。
Route::get('/ranking', function () {
    return view('auth.');
});

// ログイン
// ログインビュー。メール・パスワード入力と送信。
Route::get('/login', function () {
    return view('auth.');
});

// 会員登録
// 登録ビュー。氏名・メール・パスワードを登録。
Route::get('/register', function () {
    return view('auth.');
});