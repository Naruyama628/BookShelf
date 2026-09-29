<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class ReviewLikeController extends Controller
{
    /**
     * レビューいいね処理
     *
     * @param Review $review いいねするレビュー
     * @return RedirectResponse 書籍詳細画面
     */
    public function toggle(Review $review): RedirectResponse 
    {
        //
        $review->likedByUsers()->toggle(Auth::id());
        return back();
    }
}
