<?php

namespace App\Console\Commands;

use App\Models\ReadingPlan;
use App\Notifications\ReadingPlanReminderNotification;
use Illuminate\Console\Command;

class SendReadingPlanReminders extends Command
{
    protected $signature = 'reading-plans:send-reminders';

    protected $description = '読了予定日が近い読書計画に通知する';

    public function handle(): int
    {
        $readingPlans = ReadingPlan::with('user')
            ->whereDate('target_date', now()->addDay())
            ->get();

        foreach ($readingPlans as $readingPlan) {
            $readingPlan->user->notify(
                new ReadingPlanReminderNotification($readingPlan)
            );
        }

        return Command::SUCCESS;
    }
}