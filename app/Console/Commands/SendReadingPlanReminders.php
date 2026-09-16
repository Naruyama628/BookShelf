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
            ->where('status', ReadingPlanStatus::READING->value)
            ->where(function ($query) {
                $query->whereDate('target_date', now()->addDays(3))
                    ->whereDate('target_date', now())
                    ->whereDate('target_date', now()->subDays(3));
            })
            ->get();

            
        foreach ($readingPlans as $readingPlan) {

            if ($readingPlan->target_date->isSameDay(now()->addDays(3))) {
                $message = '読書期限の3日前です。';
            } elseif ($readingPlan->target_date->isToday()) {
                $message = '読書期限は本日です。';
            } else {
                $message = '読書期限から3日経過しています。';
            }

            $readingPlan->user->notify(
                new ReadingPlanReminderNotification($readingPlan, $message)
            );
        }

        return Command::SUCCESS;
    }
}