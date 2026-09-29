<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Book;
use App\Models\Review;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class UserModelTest extends TestCase
{
    public function test_favoriteBooksはbelongsToManyリレーションである(): void
    {
        $user = new User();

        $relation = $user->favoriteBooks();

        $this->assertInstanceOf(BelongsToMany::class, $relation);
        $this->assertInstanceOf(Book::class, $relation->getRelated());

        $this->assertEquals(
            'favorites',
            $relation->getTable()
        );

        $this->assertEquals(
            'user_id',
            $relation->getForeignPivotKeyName()
        );

        $this->assertEquals(
            'book_id',
            $relation->getRelatedPivotKeyName()
        );
    }

    public function test_likedReviewsはbelongsToManyリレーションである(): void
    {
        $user = new User();

        $relation = $user->likedReviews();

        $this->assertInstanceOf(BelongsToMany::class, $relation);
        $this->assertInstanceOf(Review::class, $relation->getRelated());

        $this->assertEquals(
            'review_likes',
            $relation->getTable()
        );

        $this->assertEquals(
            'user_id',
            $relation->getForeignPivotKeyName()
        );

        $this->assertEquals(
            'review_id',
            $relation->getRelatedPivotKeyName()
        );
    }

    public function test_reviewsはhasManyリレーションである(): void
    {
        $user = new User();

        $relation = $user->reviews();

        $this->assertInstanceOf(HasMany::class, $relation);
        $this->assertInstanceOf(Review::class, $relation->getRelated());
    }
}