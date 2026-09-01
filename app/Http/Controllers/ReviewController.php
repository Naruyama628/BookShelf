<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Book;
use App\Models\Review;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;

class ReviewController extends Controller
{
    // レビュー登録処理
    public function store(StoreReviewRequest $request, Book $book)
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

    // レビュー編集画面
    public function edit(Review $review)
    {
        //
        $this->authorize('update', $review);

        return view('reviews.edit', compact('review'));
    }

    // レビュー編集処理
    public function update(UpdateReviewRequest $request, Review $review)
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

    // レビュー削除処理
    public function destroy(Review $review)
    {
        //
        $this->authorize('delete', $review);
        
        $review->delete();
        return back()
        ->with('success', 'レビューを削除しました');
    }
}
