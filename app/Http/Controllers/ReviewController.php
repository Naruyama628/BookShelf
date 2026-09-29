<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Book;
use App\Models\Review;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ReviewController extends Controller
{
    /**
     * レビュー登録処理
     *
     * @param StoreReviewRequest $request レビュー内容
     * @param Book $book レビューする書籍
     * @return RedirectResponse 書籍詳細画面
     */
    public function store(StoreReviewRequest $request, Book $book) : RedirectResponse
    {
        //
        Review::create([
            'user_id' => Auth::id(),
            'book_id' => $book->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()
            ->with('success', 'レビューを登録しました');
    }

    /**
     * レビュー編集画面表示
     *
     * @param Review $review 編集するレビュー
     * @return RedirectResponse レビュー編集画面
     */
    public function edit(Review $review) : View
    {
        //
        $this->authorize('update', $review);

        return view('reviews.edit', compact('review'));
    }

    /**
     * レビュー編集処理
     *
     * @param UpdateReviewRequest $request 編集後レビュー内容
     * @param Review $review 編集するレビュー
     * @return RedirectResponse 書籍詳細画面
     */
    public function update(UpdateReviewRequest $request, Review $review) : RedirectResponse
    {
        //
        $this->authorize('update', $review);

        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);
        return redirect()->route('books.show', $review->book)
            ->with('success', 'レビューを編集しました');
    }

    /**
     * レビュー削除処理
     *
     * @param Review $review 削除するレビュー
     * @return RedirectResponse 書籍詳細画面
     */
    public function destroy(Review $review) : RedirectResponse
    {
        //
        $this->authorize('delete', $review);
        
        $review->delete();
        
        return back()
        ->with('success', 'レビューを削除しました');
    }
}
