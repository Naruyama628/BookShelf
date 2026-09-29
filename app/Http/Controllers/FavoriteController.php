<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\user;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class FavoriteController extends Controller
{
    /**
     * お気に入り一覧を表示
     *
     * @return View お気に入り一覧画面
     */
    public function index(): View
    {
        //
        $books = Auth::user()->favoriteBooks()->paginate(10);
        return view('favorites.index', compact('books'));
    }

    /**
     * お気に入りボタンを押した際の処理
     *
     * @param Book $book いいねボタンを押した書籍
     * @return RedirectResponse 書籍詳細画面
     */
    public function toggle(Book $book): RedirectResponse
    {
        //
        Auth::user()->favoriteBooks()->toggle($book->id);

        return back();
    }
}
