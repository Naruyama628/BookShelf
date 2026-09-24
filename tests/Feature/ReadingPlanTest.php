<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Book;
use App\Models\ReadingPlan;
use App\Enums\ReadingPlanStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReadingPlanTest extends TestCase
{
    use RefreshDatabase;
 
    public function test_自分の読書計画一覧を表示できる(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        ReadingPlan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'target_date' => today()->addDays(7),
            'status' => ReadingPlanStatus::Reading->value,
        ]);

        $response = $this->actingAs($user)
            ->get(route('reading-plans.index'));

        $response->assertStatus(200);
        $response->assertViewIs('reading-plans.index');
        $response->assertViewHas('readingPlans');
    }

    public function test_読書計画をステータスで絞り込みできる(): void
    {
        $user = User::factory()->create();

        $readingBook = Book::factory()->create();
        $completedBook = Book::factory()->create();

        ReadingPlan::create([
            'user_id' => $user->id,
            'book_id' => $readingBook->id,
            'target_date' => today()->addDays(7),
            'status' => ReadingPlanStatus::Reading->value,
        ]);

        ReadingPlan::create([
            'user_id' => $user->id,
            'book_id' => $completedBook->id,
            'target_date' => today(),
            'status' => ReadingPlanStatus::Completed->value,
            'completed_at' => now(),
        ]);

        $response = $this->actingAs($user)
            ->get(route('reading-plans.index', [
                'status' => ReadingPlanStatus::Reading->value,
            ]));

        $response->assertStatus(200);

        $response->assertViewHas('readingPlans', function ($plans) {
            return $plans->count() === 1
                && $plans->first()->status === ReadingPlanStatus::Reading;
        });
    }

    public function test_読書計画登録画面を表示できる(): void
    {
        $user = User::factory()->create();
        Book::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('reading-plans.create'));

        $response->assertStatus(200);
        $response->assertViewIs('reading-plans.create');
        $response->assertViewHas('books');
    }

    public function test_読書計画を登録できる(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('reading-plans.store'), [
                'book_id' => $book->id,
                'target_date' => today()->addDays(7)->format('Y-m-d'),
            ]);

        $response->assertRedirect(route('reading-plans.index'));

        $this->assertDatabaseHas('reading_plans', [
            'user_id' => $user->id,
            'book_id' => $book->id,
            'target_date' => today()->addDays(7)->format('Y-m-d'),
        ]);
    }

    public function test_自分の読書計画編集画面を表示できる(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        $plan = ReadingPlan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'target_date' => today()->addDays(7),
            'status' => ReadingPlanStatus::Reading->value,
        ]);

        $response = $this->actingAs($user)
            ->get(route('reading-plans.edit', $plan));

        $response->assertStatus(200);
        $response->assertViewIs('reading-plans.edit');
    }

    public function test_読了予定日を変更できる(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        $plan = ReadingPlan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'target_date' => today()->addDays(7),
            'status' => ReadingPlanStatus::Reading->value,
        ]);

        $newDate = today()->addDays(14)->format('Y-m-d');

        $response = $this->actingAs($user)
            ->put(route('reading-plans.update', $plan), [
                'target_date' => $newDate,
            ]);

        $response->assertRedirect(route('reading-plans.index'));

        $this->assertDatabaseHas('reading_plans', [
            'id' => $plan->id,
            'target_date' => $newDate,
        ]);
    }

    public function test_読書計画を読了にできる(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        $plan = ReadingPlan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'target_date' => today(),
            'status' => ReadingPlanStatus::Reading->value,
        ]);

        $response = $this->actingAs($user)
            ->post(route('reading-plans.complete', $plan));

        $response->assertRedirect(route('reading-plans.index'));

        $this->assertDatabaseHas('reading_plans', [
            'id' => $plan->id,
            'status' => ReadingPlanStatus::Completed->value,
        ]);

        $plan->refresh();

        $this->assertNotNull($plan->completed_at);
    }

    public function test_自分の読書計画を削除できる(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        $plan = ReadingPlan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'target_date' => today(),
            'status' => ReadingPlanStatus::Reading->value,
        ]);

        $response = $this->actingAs($user)
            ->delete(route('reading-plans.destroy', $plan));

        $response->assertRedirect(route('reading-plans.index'));

        $this->assertDatabaseMissing('reading_plans', [
            'id' => $plan->id,
        ]);
    }

    public function test_他人の読書計画は編集できない(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $book = Book::factory()->create();

        $plan = ReadingPlan::create([
            'user_id' => $otherUser->id,
            'book_id' => $book->id,
            'target_date' => today()->addDays(7),
            'status' => ReadingPlanStatus::Reading->value,
        ]);

        $response = $this->actingAs($user)
            ->get(route('reading-plans.edit', $plan));

        $response->assertStatus(403);
    }
}
