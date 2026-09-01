<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Book;
use App\Models\User;
use App\Models\Genre;

class BookTest extends TestCase
{
    use RefreshDatabase;

    public function test_書籍一覧画面を表示できる(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_書籍詳細画面を表示できる(): void
    {
        $book = Book::factory()->create();
        $response = $this->get(route('books.show', $book));

        $response->assertStatus(200);
    }

    public function test_書籍登録画面を表示できる(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('books.create'));

        $response->assertStatus(200);
    }

    public function test_書籍編集画面を表示できる(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->create([
            'created_by' => $user->id,
        ]);
        
        $response = $this->actingAs($user)->get(route('books.edit', $book));

        $response->assertStatus(200);
    }

    public function test_書籍を登録できる(): void
    {
        $user = User::factory()->create();
        $genre = Genre::factory()->create();

        $response = $this->actingAs($user)->post(route('books.store'), [
            'title' => 'Laravel入門',
            'author' => '山田太郎',
            'isbn' => '9781234567890',
            'published_date' => '2026-08-21',
            'description' => 'Laravelの入門書です。',
            'image_url' => 'https://example.com/image.jpg',
            'genres' => [$genre->id],
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('books', [
            'title' => 'Laravel入門',
            'isbn' => '9781234567890',
        ]);

        $this->assertDatabaseHas('book_genre', [
            'book_id' => Book::where('isbn', '9781234567890')->value('id'),
            'genre_id' => $genre->id,
        ]);
    }

    public function test_未ログインでは書籍登録画面にアクセスできない(): void
    {
        $response = $this->get(route('books.create'));

        $response->assertStatus(302);

        $response->assertRedirect(route('login'));
    }

    public function test_他人が作成した書籍は編集できない(): void
    {
        $createUser = User::factory()->create();
        $editUser = User::factory()->create();

        $book = Book::factory()->create([
            'created_by' => $createUser->id,
        ]);

        $response = $this->actingAs($editUser)->get(route('books.edit', $book));

        $response->assertStatus(403);
    }

    public function test_書籍を更新できる(): void
    {
        $user = User::factory()->create();
        $genre = Genre::factory()->create();

        $book = Book::factory()->create([
            'created_by' => $user->id,
        ]);

        $response = $this->actingAs($user)->put(route('books.update', $book), [
                'title' => 'test',
                'author' => $book->author,
                'isbn' => $book->isbn,
                'published_date' => $book->published_date,
                'description' => $book->description,
                'image_url' => $book->image_url,
                'genres' => [$genre->id],
            ]);

        $response->assertRedirect(route('books.show', $book));

        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'title' => 'test',
        ]);
    }

    public function test_書籍を削除できる(): void
    {
        $user = User::factory()->create();

        $book = Book::factory()->create([
            'created_by' => $user->id,
        ]);

        $response = $this->actingAs($user)->delete(route('books.destroy', $book));

        $response->assertRedirect(route('books.index'));

        $this->assertDatabaseMissing('books', [
            'id' => $book->id,
        ]);
    }

    public function test_ISBN重複で登録できない(): void
    {
        $user = User::factory()->create();
        $genre = Genre::factory()->create();
        
        Book::factory()->create([
            'isbn' => '1234567890123',
        ]);

        $response = $this->actingAs($user)->post(route('books.store'), [
            'title' => 'testTitle',
            'author' => 'testAuthor',
            'isbn' => '1234567890123',
            'published_date' => '2000/11/11',
            'description' => 'test',
            'image_url' => null,
            'genres' => [$genre->id],
        ]);

        $response->assertSessionHasErrors('isbn');
    }

    public function test_必須項目未入力で登録できない(): void
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->post(route('books.store'), [
            'title' => null,
            'author' => null,
            'isbn' => null,
            'published_date' => null,
            'description' => null,
            'image_url' => null,
            'genres' => null,
        ]);

        $response->assertSessionHasErrors([
            'title',
            'author',
            'isbn',
            'published_date',
            'genres',
        ]);
    }

    public function test_存在しない書籍詳細は404になる(): void
    {
        $response = $this->get(route('books.show', 999999));

        $response->assertStatus(404);
    }
}
