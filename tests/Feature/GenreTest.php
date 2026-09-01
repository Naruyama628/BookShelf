<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Genre;
use App\Models\User;
use App\Models\Book;

class GenreTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_ジャンル一覧画面を表示できる(): void
    {
        $user = User::factory()->create();

        Genre::factory()->count(3)->create();

        $response = $this->actingAs($user)
            ->get(route('genres.index'));

        $response->assertStatus(200);
    }
    
    public function test_ジャンル登録画面を表示できる(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('genres.create'));

        $response->assertStatus(200);
    }

    public function test_ジャンル詳細画面を表示できる(): void
    {
        $user = User::factory()->create();
        $genre = Genre::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('genres.show', $genre));

        $response->assertStatus(200);
    }

    public function test_ジャンル編集画面を表示できる(): void
    {
        $user = User::factory()->create();
        $genre = Genre::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('genres.edit', $genre));

        $response->assertStatus(200);
    }

    public function test_ジャンルを登録できる(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('genres.store'), [
            'name' => 'プログラミング',
        ]);

        $this->assertDatabaseHas('genres', [
            'name' => 'プログラミング',
        ]);
    }

    public function test_ジャンルを更新できる(): void
    {
        $user = User::factory()->create();

        $genre = Genre::factory()->create([
            'name' => '小説',
        ]);

        $response = $this->actingAs($user)->put(
            route('genres.update', $genre),
            [
                'name' => '技術書',
            ]
        );

        $this->assertDatabaseHas('genres', [
            'id' => $genre->id,
            'name' => '技術書',
        ]);
    }

    public function test_書籍が紐づいていないジャンルを削除できる(): void
    {
        $user = User::factory()->create();
        $genre = Genre::factory()->create();

        $response = $this->actingAs($user)
            ->delete(route('genres.destroy', $genre));

        $response->assertRedirect(route('genres.index'));

        $this->assertDatabaseMissing('genres', [
            'id' => $genre->id,
        ]);
    }

    public function test_書籍が紐づいているジャンルは削除できない(): void
    {
        $user = User::factory()->create();
        $genre = Genre::factory()->create();
        $book = Book::factory()->create();

        $genre->books()->attach($book->id);

        $response = $this->actingAs($user)
            ->delete(route('genres.destroy', $genre));

        $response->assertRedirect(route('genres.index'));

        $response->assertSessionHas(
            'error',
            'このジャンルに紐づく書籍が存在するため、削除できません'
        );

        $this->assertDatabaseHas('genres', [
            'id' => $genre->id,
        ]);
    }

    public function test_名前未入力では登録できない(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('genres.store'), [
            'name' => null,
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_ジャンル名重複では登録できない(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('genres.store'), [
            'name' => 'プログラミング',
        ]);

        $this->assertDatabaseHas('genres', [
            'name' => 'プログラミング',
        ]);
        
        $response = $this->actingAs($user)->post(route('genres.store'), [
            'name' => 'プログラミング',
        ]);

        $response->assertSessionHasErrors('name');
    }
}
