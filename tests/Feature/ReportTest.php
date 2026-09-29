<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Book;
use App\Models\Genre;
use App\Models\Review;
use App\Models\ReadingPlan;
use App\Enums\ReadingPlanStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_読書レポートを表示できる(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('reports.index'));

        $response->assertStatus(200);
        $response->assertViewIs('reports.index');
        $response->assertViewHas('stats');
    }

    public function test_読書レポートのサマリーを正しく集計できる(): void
    {
        $user = User::factory()->create();

        $book1 = Book::factory()->create();
        $book2 = Book::factory()->create();

        Review::factory()->create([
            'user_id' => $user->id,
            'book_id' => $book1->id,
            'rating' => 5,
        ]);

        Review::factory()->create([
            'user_id' => $user->id,
            'book_id' => $book2->id,
            'rating' => 3,
        ]);

        ReadingPlan::create([
            'user_id' => $user->id,
            'book_id' => $book1->id,
            'target_date' => today(),
            'status' => ReadingPlanStatus::Completed->value,
            'completed_at' => now(),
        ]);

        $response = $this->actingAs($user)
            ->get(route('reports.index'));

        $response->assertViewHas('stats', function ($stats) {
            return $stats['summary']['total_reviews'] === 2
                && $stats['summary']['books_read'] === 1
                && (float) $stats['summary']['average_rating'] === 4.0;
        });
    }

    public function test_評価分布を正しく集計できる(): void
    {
        $user = User::factory()->create();

        foreach ([5, 4, 3] as $rating) {
            Review::factory()->create([
                'user_id' => $user->id,
                'book_id' => Book::factory()->create()->id,
                'rating' => $rating,
            ]);
        }

        $response = $this->actingAs($user)
            ->get(route('reports.index'));

        $response->assertViewHas('stats', function ($stats) {
            return $stats['rating_distribution'][4] === 1
                && $stats['rating_distribution'][3] === 1
                && $stats['rating_distribution'][2] === 1
                && $stats['rating_distribution'][1] === 0
                && $stats['rating_distribution'][0] === 0;
        });
    }

    public function test_評価4以上の書籍だけ高評価書籍に含まれる(): void
    {
        $user = User::factory()->create();

        $highBook = Book::factory()->create([
            'title' => '高評価の本',
        ]);

        $lowBook = Book::factory()->create([
            'title' => '低評価の本',
        ]);

        Review::factory()->create([
            'user_id' => $user->id,
            'book_id' => $highBook->id,
            'rating' => 5,
        ]);

        Review::factory()->create([
            'user_id' => $user->id,
            'book_id' => $lowBook->id,
            'rating' => 2,
        ]);

        $response = $this->actingAs($user)
            ->get(route('reports.index'));

        $response->assertViewHas('stats', function ($stats) {
            $books = $stats['top_rated_books'];

            return $books->count() === 1
                && $books->first()['title'] === '高評価の本'
                && $books->first()['rating'] === 5;
        });
    }

    public function test_他のユーザーのレビューはレポートに含まれない(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        Review::factory()->create([
            'user_id' => $user->id,
            'book_id' => Book::factory()->create()->id,
            'rating' => 5,
        ]);

        Review::factory()->create([
            'user_id' => $otherUser->id,
            'book_id' => Book::factory()->create()->id,
            'rating' => 1,
        ]);

        $response = $this->actingAs($user)
            ->get(route('reports.index'));

        $response->assertViewHas('stats', function ($stats) {
            return $stats['summary']['total_reviews'] === 1
                && (float) $stats['summary']['average_rating'] === 5.0;
        });
    }
}