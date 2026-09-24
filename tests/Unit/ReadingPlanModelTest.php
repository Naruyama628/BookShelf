<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Book;
use App\Models\ReadingPlan;
use App\Enums\ReadingPlanStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReadingPlanModelTest extends TestCase
{
    public function test_userはbelongsToリレーションである(): void
    {
        $readingPlan = new ReadingPlan();

        $relation = $readingPlan->user();

        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertInstanceOf(User::class, $relation->getRelated());
    }

    public function test_bookはbelongsToリレーションである(): void
    {
        $readingPlan = new ReadingPlan();

        $relation = $readingPlan->book();

        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertInstanceOf(Book::class, $relation->getRelated());
    }

    public function test_fillableが正しく設定されている(): void
    {
        $readingPlan = new ReadingPlan();

        $this->assertEquals([
            'user_id',
            'book_id',
            'target_date',
            'status',
            'started_at',
            'completed_at',
        ], $readingPlan->getFillable());
    }

    public function test_castsが正しく設定されている(): void
    {
        $readingPlan = new ReadingPlan();

        $casts = $readingPlan->getCasts();

        $this->assertEquals('date', $casts['target_date']);
        $this->assertEquals('datetime', $casts['started_at']);
        $this->assertEquals('datetime', $casts['completed_at']);
        $this->assertEquals(
            ReadingPlanStatus::class,
            $casts['status']
        );
    }
}