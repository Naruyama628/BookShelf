<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_レビュー編集画面を表示できる(): void
    {
        $user = User::factory()->create();

        $review = Review::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)
            ->get(route('reviews.edit', $review));

        $response->assertStatus(200);
    }

    public function test_ログインユーザーはレビューを投稿できる(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('reviews.store', $book), [
                'rating' => 5,
                'comment' => 'とても面白かったです。',
            ]);

        $this->assertDatabaseHas('reviews', [
            'user_id' => $user->id,
            'book_id' => $book->id,
            'rating' => 5,
            'comment' => 'とても面白かったです。',
        ]);
    }

    public function test_未ログインユーザーはレビューを投稿できない(): void
    {
        $book = Book::factory()->create();

        $response = $this->post(route('reviews.store', $book), [
            'rating' => 5,
            'comment' => 'テストレビュー',
        ]);

        $response->assertRedirect(route('login'));
    }

    public function test_レビューを更新できる(): void
    {
        $user = User::factory()->create();

        $review = Review::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)
            ->put(route('reviews.update', $review), [
                'rating' => 3,
                'comment' => '更新しました。',
            ]);

        $this->assertDatabaseHas('reviews', [
            'id' => $review->id,
            'rating' => 3,
            'comment' => '更新しました。',
        ]);
    }

    public function test_レビューを削除できる(): void
    {
        $user = User::factory()->create();

        $review = Review::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)
            ->delete(route('reviews.destroy', $review));

        $this->assertDatabaseMissing('reviews', [
            'id' => $review->id,
        ]);
    }

    public function test_評価値未入力ではレビューを投稿できない(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('reviews.store', $book), [
                'rating' => null,
                'comment' => 'テスト',
            ]);

        $response->assertSessionHasErrors('rating');
    }

    public function test_評価値は1から5の範囲でなければならない(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('reviews.store', $book), [
                'rating' => 6,
                'comment' => 'テスト',
            ]);

        $response->assertSessionHasErrors('rating');
    }

    public function test_同じ書籍に重複してレビューできない(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        Review::factory()->create([
            'user_id' => $user->id,
            'book_id' => $book->id,
        ]);

        $response = $this->actingAs($user)
            ->post(route('reviews.store', $book), [
                'rating' => 4,
                'comment' => '2回目のレビュー',
            ]);

        $response->assertSessionHasErrors();
    }
}