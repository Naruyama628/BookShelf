<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Genre;
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_書籍一覧APIを取得できる(): void
    {
        Book::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/books');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'links',
                'meta',
            ]);
    }

    public function test_書籍詳細APIを取得できる(): void
    {
        $book = Book::factory()->create();

        $response = $this->getJson("/api/v1/books/{$book->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.title', $book->title);
    }

    public function test_存在しない書籍詳細は404になる(): void
    {
        $response = $this->getJson('/api/v1/books/999999');

        $response->assertNotFound();
    }

    public function test_キーワードで書籍を検索できる(): void
    {
        Book::factory()->create([
            'title' => 'Laravel入門',
            'author' => '山田太郎',
        ]);

        Book::factory()->create([
            'title' => 'PHP入門',
            'author' => '佐藤太郎',
        ]);

        $response = $this->getJson(
            '/api/v1/books?keyword=Laravel'
        );

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.title', 'Laravel入門');
    }

    public function test_ジャンルで書籍を絞り込める(): void
    {
        $genre = Genre::factory()->create();

        $book = Book::factory()->create();

        $book->genres()->attach($genre->id);

        Book::factory()->create();

        $response = $this->getJson(
            "/api/v1/books?genre={$genre->id}"
        );

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.title', $book->title);
    }

    public function test_一覧に平均評価とレビュー件数が含まれる(): void
    {
        $book = Book::factory()->create();

        Review::factory()->create([
            'book_id' => $book->id,
            'rating' => 5,
        ]);

        Review::factory()->create([
            'book_id' => $book->id,
            'rating' => 3,
        ]);

        $response = $this->getJson('/api/v1/books');

        $response->assertStatus(200)
            ->assertJsonPath('data.0.reviews_count', 2);
    }

    public function test_APIで書籍を登録できる(): void
    {
        $genre = Genre::factory()->create();

        $response = $this->postJson('/api/v1/books', [
            'title' => 'API Laravel入門',
            'author' => '山田太郎',
            'isbn' => '9781234567890',
            'published_date' => '2026-08-24',
            'description' => 'API登録テストです。',
            'image_url' => 'https://example.com/book.jpg',
            'genres' => [$genre->id],
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('books', [
            'title' => 'API Laravel入門',
            'isbn' => '9781234567890',
        ]);

        $book = Book::where('isbn', '9781234567890')->first();

        $this->assertDatabaseHas('book_genre', [
            'book_id' => $book->id,
            'genre_id' => $genre->id,
        ]);
    }

    public function test_APIで書籍を更新できる(): void
    {
        $book = Book::factory()->create();
        $genre = Genre::factory()->create();

        $response = $this->putJson(
            "/api/v1/books/{$book->id}",
            [
                'title' => 'API更新後タイトル',
                'author' => $book->author,
                'isbn' => $book->isbn,
                'published_date' => $book->published_date,
                'description' => $book->description,
                'image_url' => $book->image_url,
                'genres' => [$genre->id],
            ]
        );

        $response->assertStatus(200);

        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'title' => 'API更新後タイトル',
        ]);

        $this->assertDatabaseHas('book_genre', [
            'book_id' => $book->id,
            'genre_id' => $genre->id,
        ]);
    }

    public function test_APIで書籍を削除できる(): void
    {
        $book = Book::factory()->create();

        $response = $this->deleteJson(
            "/api/v1/books/{$book->id}"
        );

        $response->assertStatus(204);

        $this->assertDatabaseMissing('books', [
            'id' => $book->id,
        ]);
    }
}