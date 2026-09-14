<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;
use App\Models\Genre;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    //

    // 書籍一覧
    public function index(Request $request) : View
    {
        $query = Book::search($request->keyword, $request->genre)
            ->withAvg('reviews', 'rating');

        switch($request->sort)
        {
            case 'newest':
                $query->orderByDesc('created_at');
                break;

            case 'oldest':
                $query->orderBy('created_at');
                break;

            case 'rating':
                $query->orderByDesc('reviews_avg_rating');
                break;

            case 'title':
                $query->orderBy('title');
                break;
        }

        $books = $query->paginate(10);
        $books->load('genres');

        $genres = Genre::all();

        return view('books.index', compact('books', 'genres'));
    }

    // 書籍詳細
    public function show(Book $book) : View
    {
        $book->load([
            'genres',
            'reviews.user',
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
    public function store(StoreBookRequest $request) : RedirectResponse {
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

        return redirect()->route('books.index')
            ->with('success', '書籍を登録しました');
    }

    // 書籍の更新処理
    public function update(Book $book, UpdateBookRequest $request) : RedirectResponse {
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

        return redirect()->route('books.show', $book->id)
            ->with('success', '書籍を更新しました');
    }

    // 書籍の削除処理
    public function destroy(Book $book)
    {
        //
        $this->authorize('delete', $book);

        $book->delete();
        return redirect()->route('books.index')
            ->with('success', '書籍を削除しました');
    }

    //
    public function searchByIsbn(string $isbn)
    {
        if (!preg_match('/^\d{13}$/', $isbn)) {
            return response()->json([
                'error' => 'ISBNは13桁の数字で入力してください。',
            ], 422);
        }

        $response = Http::get(
            'https://www.googleapis.com/books/v1/volumes',
            [
                'q' => 'isbn:' . $isbn,
                'key' => config('services.google_books.key'),
            ]
        );

        if ($response->failed()) {
            return response()->json([
                'error' => 'Google Books APIとの通信に失敗しました。',
            ], 502);
        }

        $data = $response->json();

        if (empty($data['items'])) {
            return response()->json([
                'error' => '書籍情報が見つかりませんでした。',
            ], 404);
        }

        $volumeInfo = $data['items'][0]['volumeInfo'];

        return response()->json([
            'title' => $volumeInfo['title'] ?? '',
            'author' => isset($volumeInfo['authors'])
                ? implode(', ', $volumeInfo['authors'])
                : '',
            'published_date' => $volumeInfo['publishedDate'] ?? '',
            'description' => $volumeInfo['description'] ?? '',
            'image_url' => $volumeInfo['imageLinks']['thumbnail'] ?? '',
        ]);
    }
}