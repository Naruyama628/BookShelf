<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\user; //いらないかも
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $books = Auth::user()->favoriteBooks()->paginate(10);
        return view('favorites.index', compact('books'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function toggle(Book $book)
    {
        //
        Auth::user()->favoriteBooks()->toggle($book->id);

        return back();
    }
}
