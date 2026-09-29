<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookModelTest extends TestCase
{
    public function test_creatorはbelongsToリレーションである(): void
    {
        $book = new Book();

        $relation = $book->creator();

        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertInstanceOf(User::class, $relation->getRelated());
        $this->assertEquals('created_by', $relation->getForeignKeyName());
    }

    public function test_genresはbelongsToManyリレーションである(): void
    {
        $book = new Book();

        $relation = $book->genres();

        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\BelongsToMany::class,
            $relation
        );
    }

    public function test_reviewsはhasManyリレーションである(): void
    {
        $book = new Book();

        $relation = $book->reviews();

        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\HasMany::class,
            $relation
        );
    }
}