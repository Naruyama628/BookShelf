<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\View\View;

class RankingController extends Controller
{
    /**
     * ランキング画面表示
     *
     * @return View ランキング画面
     */
    public function index(): View
    {
        //
        $rankedBooks = Book::has('reviews')
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->orderByDesc('reviews_avg_rating')
            ->orderByDesc('reviews_count')
            ->limit(10)
            ->get();

        return view('ranking.index', compact('rankedBooks'));
    }
}
