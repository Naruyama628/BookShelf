<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ReviewLikeController extends Controller
{
    // 書籍お気に入り処理
    public function toggle(Review $review)
    {
        //
        $review->likedByUsers()->toggle(Auth::id());
        return back();
    }
}
