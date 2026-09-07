<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Book;
use App\Models\Genre;

class ReportController extends Controller
{
    //

    public function index(Request $request) : View
    {
        $stats = [
            'summary' => [
                'total_reviews' => 4,
                'books_read' => 1,
                'average_rating' => 10000000000,
            ],
            'rating_distribution' => collect([
                4 => 1,
                3 => 2,
                2 => 3,
                1 => 4,
                0 => 4,
            ]),
            'top_rated_books' => [
                1 => Book::find(1),
                2 => Book::find(2),
                3 => Book::find(3),
            ],
            'genre_ratings' => Genre::all(),
        ];

        return view('reports.index', compact('stats'));
    }
}
