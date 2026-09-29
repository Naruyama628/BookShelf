<?php

use Tests\TestCase;
use App\Models\User;
use App\Models\Book;
use App\Models\ReadingPlan;
use App\Enums\ReadingPlanStatus;
use App\Notifications\ReadingPlanReminderNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReadingPlanReminderTest extends TestCase
{
    use RefreshDatabase;

    public function test_読了予定日の3日前に通知される(): void
    {
        Notification::fake();

        $user = User::factory()->create();
        $book = Book::factory()->create();

        $plan = ReadingPlan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'target_date' => today()->addDays(3),
            'status' => ReadingPlanStatus::Reading->value,
        ]);

        $this->artisan('reading-plans:send-reminders')
            ->assertSuccessful();

        Notification::assertSentTo(
            $user,
            ReadingPlanReminderNotification::class
        );
    }

    public function test_読了予定日当日に通知される(): void
    {
        Notification::fake();

        $user = User::factory()->create();
        $book = Book::factory()->create();

        ReadingPlan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'target_date' => today(),
            'status' => ReadingPlanStatus::Reading->value,
        ]);

        $this->artisan('reading-plans:send-reminders')
            ->assertSuccessful();

        Notification::assertSentTo(
            $user,
            ReadingPlanReminderNotification::class,
            function ($notification) use ($user) {

                $data = $notification->toArray($user);

                return $data['timing'] === 'on_due_date'
                    && $data['body'] === '読書期限は本日です。';
            }
        );
    }

    public function test_読了予定日から3日後に通知される(): void
    {
        Notification::fake();

        $user = User::factory()->create();
        $book = Book::factory()->create();

        ReadingPlan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'target_date' => today()->subDays(3),
            'status' => ReadingPlanStatus::Reading->value,
        ]);

        $this->artisan('reading-plans:send-reminders')
            ->assertSuccessful();

        Notification::assertSentTo(
            $user,
            ReadingPlanReminderNotification::class,
            function ($notification) use ($user) {

                $data = $notification->toArray($user);

                return $data['timing'] === 'three_days_after'
                    && $data['body'] === '読書期限から3日経過しています。';
            }
        );
    }

    public function test_通知対象日以外は通知されない(): void
    {
        Notification::fake();

        $user = User::factory()->create();
        $book = Book::factory()->create();

        ReadingPlan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'target_date' => today()->addDays(7),
            'status' => ReadingPlanStatus::Reading->value,
        ]);

        $this->artisan('reading-plans:send-reminders')
            ->assertSuccessful();

        Notification::assertNothingSent();
    }

    public function test_読了済みの読書計画には通知されない(): void
    {
        Notification::fake();

        $user = User::factory()->create();
        $book = Book::factory()->create();

        ReadingPlan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'target_date' => today(),
            'status' => ReadingPlanStatus::Completed->value,
            'completed_at' => now(),
        ]);

        $this->artisan('reading-plans:send-reminders')
            ->assertSuccessful();

        Notification::assertNothingSent();
    }

    public function test_自分の通知一覧を表示できる(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        $plan = ReadingPlan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'target_date' => today()->addDays(3),
            'status' => 'reading',
        ]);

        $user->notify(
            new ReadingPlanReminderNotification(
                $plan,
                '読書期限から3日経過しています。',
                'on_due_date'
            )
        );

        $response = $this->actingAs($user)
            ->get(route('notifications.index'));

        $response->assertStatus(200);
        $response->assertViewIs('notifications.index');
        $response->assertViewHas('notifications');
    }

    public function test_自分の通知を既読にできる(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        $plan = ReadingPlan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'target_date' => today()->addDays(3),
            'status' => 'reading',
        ]);

        $user->notify(
            new ReadingPlanReminderNotification(
                $plan,
                '読書期限から3日経過しています。',
                'on_due_date'
            )
        );

        $notification = $user->notifications()->first();

        $this->assertNull($notification->read_at);

        $response = $this->actingAs($user)
            ->post(
                route('notifications.read', $notification->id)
            );

        $response->assertRedirect();

        $notification->refresh();

        $this->assertNotNull($notification->read_at);
    }

    public function test_他人の通知を既読にできない(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $book = Book::factory()->create();

        $plan = ReadingPlan::create([
            'user_id' => $otherUser->id,
            'book_id' => $book->id,
            'target_date' => today()->addDays(3),
            'status' => 'reading',
        ]);

        $otherUser->notify(
            new ReadingPlanReminderNotification(
                $plan,
                '読書期限は本日です。',
                'on_due_date'
            )
        );

        $notification = $otherUser->notifications()->first();

        $response = $this->actingAs($user)
            ->post(
                route('notifications.read', $notification->id)
            );

        $response->assertStatus(404);

        $notification->refresh();

        $this->assertNull($notification->read_at);
    }
}