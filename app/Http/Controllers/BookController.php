<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Genre;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    //

    // 書籍一覧
    public function index(): View
    {
        $books = Book::paginate(10);
        return view('books.index', compact('books'));
    }

    // 書籍詳細
    public function show(Book $book) : View
    {
        $book->load([
            'genres',
            'reviews',
        ]);
        
        return view('books.show', compact('book'));
    }

    // 書籍登録画面
    public function create() : View
    {
        $genres = Genre::all();
        return view('books.create', compact('genres'));
    }

    // 書籍編集画面
    public function edit(Book $book) : View
    {
        $this->authorize('update', $book);

        $book->load([
            'genres',
        ]);
        $genres = Genre::all();

        return view('books.edit', compact('book', 'genres'));
    }

    // 書籍の登録処理
    public function store(Request $request) : RedirectResponse {
        $book = Book::create([
            'title' => $request->title,
            'author' => $request->author,
            'isbn' => $request->isbn,
            'published_date' => $request->published_date,
            'description' => $request->description,
            'image_url' => $request->image_url,
            'created_by' => Auth::id(),
        ]);
        $book->genres()->sync($request->genres);

        return redirect()->route('books.index');
    }

    // 書籍の更新処理
    public function update(Book $book, Request $request) : RedirectResponse {
        $this->authorize('update', $book);

        $book->update([
            'title' => $request->title,
            'author' => $request->author,
            'isbn' => $request->isbn,
            'published_date' => $request->published_date,
            'description' => $request->description,
            'image_url' => $request->image_url,
        ]);
        $book->genres()->sync($request->genres);

        return redirect()->route('books.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        //
        $this->authorize('delete', $book);
        
        $book->delete();
        return redirect()->route('books.index');
    }
}