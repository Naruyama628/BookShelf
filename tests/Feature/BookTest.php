<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Book;
use App\Models\User;
use App\Models\Genre;
use App\Models\Review;
use Illuminate\Support\Facades\Http;

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
            'genres',
        ]);
    }

    public function test_存在しない書籍詳細は404になる(): void
    {
        $response = $this->get(route('books.show', 999999));

        $response->assertStatus(404);
    }

    public function test_書籍一覧でタイトル検索できる(): void
    {
        Book::factory()->create([
            'title' => 'Laravel入門',
            'author' => '山田太郎',
        ]);

        Book::factory()->create([
            'title' => 'PHP実践',
            'author' => '鈴木花子',
        ]);

        $response = $this->get('/?keyword=Laravel');

        $response->assertStatus(200);
        $response->assertSee('Laravel入門');
        $response->assertDontSee('PHP実践');
    }

    public function test_書籍一覧で著者検索できる(): void
    {
        Book::factory()->create([
            'title' => 'Laravel入門',
            'author' => '山田太郎',
        ]);

        Book::factory()->create([
            'title' => 'PHP実践',
            'author' => '鈴木花子',
        ]);

        $response = $this->get('/?keyword=山田');

        $response->assertStatus(200);
        $response->assertSee('Laravel入門');
        $response->assertDontSee('PHP実践');
    }

    public function test_ISBN検索で書籍情報を取得できる(): void
    {
        Http::fake([
            'www.googleapis.com/*' => Http::response([
                'totalItems' => 1,
                'items' => [
                    [
                        'volumeInfo' => [
                            'title' => 'Laravel入門',
                            'authors' => ['山田太郎'],
                            'publishedDate' => '2026-08-24',
                            'description' => 'Laravelの入門書です。',
                            'imageLinks' => [
                                'thumbnail' => 'https://example.com/book.jpg',
                            ],
                        ],
                    ],
                ],
            ], 200),
        ]);

        $response = $this->getJson(
            route('books.isbn', [
                'isbn' => '9781234567890',
            ])
        );

        $response->assertStatus(200)
            ->assertJson([
                'title' => 'Laravel入門',
                'author' => '山田太郎',
            ]);
    }

    public function test_ISBNが13桁でない場合検索できない(): void
    {
        $response = $this->getJson(
            route('books.isbn', [
                'isbn' => '123456',
            ])
        );

        $response->assertStatus(422)
            ->assertJson([
                'error' => 'ISBNは13桁の数字で入力してください。',
            ]);
    }

    public function test_ISBNに数字以外が含まれる場合検索できない(): void
    {
        $response = $this->getJson(
            route('books.isbn', [
                'isbn' => '978123456789A',
            ])
        );

        $response->assertStatus(422)
            ->assertJson([
                'error' => 'ISBNは13桁の数字で入力してください。',
            ]);
    }

    public function test_GoogleBooksAPIとの通信に失敗した場合エラーになる(): void
    {
        Http::fake([
            'www.googleapis.com/*' => Http::response([], 500),
        ]);

        $response = $this->getJson(
            route('books.isbn', [
                'isbn' => '9781234567890',
            ])
        );

        $response->assertStatus(502)
            ->assertJson([
                'error' => 'Google Books APIとの通信に失敗しました。',
            ]);
    }

    public function test_ISBNに対応する書籍が存在しない場合404になる(): void
    {
        Http::fake([
            'www.googleapis.com/*' => Http::response([
                'totalItems' => 0,
                'items' => [],
            ], 200),
        ]);

        $response = $this->getJson(
            route('books.isbn', [
                'isbn' => '9781234567890',
            ])
        );

        $response->assertStatus(404)
            ->assertJson([
                'error' => '書籍情報が見つかりませんでした。',
            ]);
    }

    public function test_書籍を新しい順に並び替えできる(): void
    {
        Book::factory()->create([
            'title' => '古い本',
            'created_at' => now()->subDays(2),
        ]);

        Book::factory()->create([
            'title' => '新しい本',
            'created_at' => now(),
        ]);

        $response = $this->get('/?sort=newest');

        $response->assertStatus(200);
        $response->assertSeeInOrder([
            '新しい本',
            '古い本',
        ]);
    }

    public function test_書籍を古い順に並び替えできる(): void
    {
        Book::factory()->create([
            'title' => '古い本',
            'created_at' => now()->subDays(2),
        ]);

        Book::factory()->create([
            'title' => '新しい本',
            'created_at' => now(),
        ]);

        $response = $this->get('/?sort=oldest');

        $response->assertStatus(200);
        $response->assertSeeInOrder([
            '古い本',
            '新しい本',
        ]);
    }

    public function test_書籍をタイトル順に並び替えできる(): void
    {
        Book::factory()->create([
            'title' => 'PHP入門',
        ]);

        Book::factory()->create([
            'title' => 'Laravel入門',
        ]);

        $response = $this->get('/?sort=title');

        $response->assertStatus(200);
        $response->assertSeeInOrder([
            'Laravel入門',
            'PHP入門',
        ]);
    }

    public function test_書籍を評価が高い順に並び替えできる(): void
    {
        $highBook = Book::factory()->create([
            'title' => '高評価の本',
        ]);

        $lowBook = Book::factory()->create([
            'title' => '低評価の本',
        ]);

        Review::factory()->create([
            'book_id' => $highBook->id,
            'rating' => 5,
        ]);

        Review::factory()->create([
            'book_id' => $lowBook->id,
            'rating' => 2,
        ]);

        $response = $this->get('/?sort=rating');

        $response->assertStatus(200);
        $response->assertSeeInOrder([
            '高評価の本',
            '低評価の本',
        ]);
    }
}
