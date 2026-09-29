<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Book;
use App\Models\Genre;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class GenreModelTest extends TestCase
{
    public function test_booksはbelongsToManyリレーションである(): void
    {
        $genre = new Genre();

        $relation = $genre->books();

        $this->assertInstanceOf(BelongsToMany::class, $relation);
        $this->assertInstanceOf(Book::class, $relation->getRelated());
    }
}