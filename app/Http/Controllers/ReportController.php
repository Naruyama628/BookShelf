<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Book;
use App\Models\Genre;
use App\Models\ReadingPlan;
use App\Enums\ReadingPlanStatus;

class ReportController extends Controller
{
    //

    public function index(Request $request) : View
    {
        $stats = [
            'summary' => [
                'total_reviews' => auth()->user()->reviews()->count(),
                'books_read' => ReadingPlan::where('user_id', auth()->user()->id)
                    ->where('status', ReadingPlanStatus::Completed)
                    ->count(),
                'average_rating' => auth()->user()->reviews()->avg('rating'),
            ],
            'rating_distribution' => collect([
                4 => auth()->user()->reviews()->where('rating', 5)->count(),
                3 => auth()->user()->reviews()->where('rating', 4)->count(),
                2 => auth()->user()->reviews()->where('rating', 3)->count(),
                1 => auth()->user()->reviews()->where('rating', 2)->count(),
                0 => auth()->user()->reviews()->where('rating', 1)->count(),
            ]),
            'top_rated_books' => auth()->user()
                ->reviews()
                ->with('book')
                ->where('rating', '>=', 4)
                ->orderByDesc('rating')
                ->limit(5)
                ->get()
                ->map(function ($review) {
                    return [
                        'id' => $review->book->id,
                        'title' => $review->book->title,
                        'author' => $review->book->author,
                        'rating' => $review->rating,
                    ];
                }),

            'genre_ratings' => Genre::select('genres.id', 'genres.name')
                ->join('book_genre', 'genres.id', '=', 'book_genre.genre_id')
                ->join('books', 'book_genre.book_id', '=', 'books.id')
                ->join('reviews', 'books.id', '=', 'reviews.book_id')
                ->where('reviews.user_id', auth()->id())
                ->selectRaw('AVG(reviews.rating) as average_rating')
                ->selectRaw('COUNT(reviews.id) as count')
                ->groupBy('genres.id', 'genres.name')
                ->orderByDesc('average_rating')
                ->limit(5)
                ->get(),
        ];

        return view('reports.index', compact('stats'));
    }
}
