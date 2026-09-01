<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\user; //いらないかも
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    // いいねした書籍の一覧
    public function index()
    {
        //
        $books = Auth::user()->favoriteBooks()->paginate(10);
        return view('favorites.index', compact('books'));
    }

    // 書籍のいいね処理
    public function toggle(Book $book)
    {
        //
        Auth::user()->favoriteBooks()->toggle($book->id);

        return back();
    }
}
