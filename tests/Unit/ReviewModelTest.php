<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Book;
use App\Models\User;
use App\Models\Review;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ReviewModelTest extends TestCase
{
    public function test_bookはbelongsToリレーションである(): void
    {
        $review = new Review();

        $relation = $review->book();

        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertInstanceOf(Book::class, $relation->getRelated());
    }

    public function test_userはbelongsToリレーションである(): void
    {
        $review = new Review();

        $relation = $review->user();

        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertInstanceOf(User::class, $relation->getRelated());
    }

    public function test_likedByUsersはbelongsToManyリレーションである(): void
    {
        $review = new Review();

        $relation = $review->likedByUsers();

        $this->assertInstanceOf(BelongsToMany::class, $relation);
        $this->assertInstanceOf(User::class, $relation->getRelated());

        $this->assertEquals(
            'review_likes',
            $relation->getTable()
        );

        $this->assertEquals(
            'review_id',
            $relation->getForeignPivotKeyName()
        );

        $this->assertEquals(
            'user_id',
            $relation->getRelatedPivotKeyName()
        );
    }
}