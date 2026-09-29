<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use App\Http\Resources\Api\V1\BookResource;
use App\Http\Resources\Api\V1\BookDetailResource;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BookController extends Controller
{
    /**
     * 書籍一覧を取得
     *
     * @param Request $request 書籍検索用のキーワード、ジャンル
     * @return AnonymousResourceCollection 書籍一覧
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        //
        $books = Book::with('genres')
            ->search($request->keyword, $request->genre)
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->paginate(10);

        return BookResource::collection($books);
    }

    /**
     * 書籍を登録
     *
     * @param Request $request 書籍登録情報
     * @return JsonResponse
     */
    public function store(StoreBookRequest $request): JsonResponse
    {
        //
        $book = Book::create([
            'title' => $request->title,
            'author' => $request->author,
            'isbn' => $request->isbn,
            'published_date' => $request->published_date,
            'description' => $request->description,
            'image_url' => $request->image_url,
            'created_by' => $request->user()->id,
        ]);

        $book->genres()->sync($request->genres);
        $book->load('genres');

        return response()->json([
            'message' => '書籍を登録しました',
            'book' => new BookDetailResource($book),
        ], 201);
    }

    /**
     * 書籍詳細を取得
     *
     * @param Book $book 詳細を取得する書籍
     * @return BookDetailResource 書籍の詳細
     */
    public function show(Book $book): BookDetailResource
    {
        //
        $book->load([
            'genres',
            'reviews.user'
        ]);

        return new BookDetailResource($book);
    }

    /**
     * 書籍を更新
     *
     * @param UpdateBookRequest $request 更新後の情報
     * @param Book $book 更新する書籍
     * @return JsonResponse 
     */
    public function update(UpdateBookRequest $request, Book $book): JsonResponse
    {
        //
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
        $book->load('genres');

        return response()->json([
            'massage' => '書籍を更新しました',
            'book' => new BookDetailResource($book),
        ], 200);
    }

    /**
     * 書籍を削除
     *
     * @param Book $book 削除する書籍
     * @return JsonResponse 
     */
    public function destroy(Book $book): Response
    {
        //
        $this->authorize('update', $book);
        $book->delete();

        return response()->noContent();
    }
}
