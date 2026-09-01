<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Review;
use App\Models\User;
use App\Models\Book;

class ReviewLikeTest extends TestCase
{
    /**
     * A basic feature test example.
     */
     use RefreshDatabase;

     public function test_お気に入り書籍一覧を表示できる(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        $user->favoriteBooks()->attach($book->id);

        $response = $this->actingAs($user)
            ->get(route('favorites.index'));

        $response->assertStatus(200);

        $response->assertViewHas('books');
    }

    public function test_ログインユーザーはレビューにいいねできる(): void
    {
        $user = User::factory()->create();
        $review = Review::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('reviews.like', $review));

        $this->assertDatabaseHas('review_likes', [
            'user_id' => $user->id,
            'review_id' => $review->id,
        ]);
    }

    public function test_いいね済みレビューを再度押すといいね解除できる(): void
    {
        $user = User::factory()->create();
        $review = Review::factory()->create();

        $user->likedReviews()->attach($review->id);

        $response = $this->actingAs($user)
            ->post(route('reviews.like', $review));

        $this->assertDatabaseMissing('review_likes', [
            'user_id' => $user->id,
            'review_id' => $review->id,
        ]);
    }

    public function test_未ログインユーザーはレビューにいいねできない(): void
    {
        $review = Review::factory()->create();

        $response = $this->post(
            route('reviews.like', $review)
        );

        $response->assertRedirect(route('login'));

        $this->assertDatabaseMissing('review_likes', [
            'review_id' => $review->id,
        ]);
    }
}
