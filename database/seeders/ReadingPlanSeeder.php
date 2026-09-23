<?php

namespace Database\Seeders;

use App\Models\ReadingPlan;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\Enums\ReadingPlanStatus;

class ReadingPlanSeeder extends Seeder
{
    public function run(): void
    {
        $today = Carbon::today();

        // 山田太郎：主要シナリオ

        // 1. 3日前リマインダー対象
        ReadingPlan::create([
            'user_id' => 1,
            'book_id' => 1,
            'target_date' => $today->copy()->addDays(3),
            'status' => ReadingPlanStatus::Reading,
            'started_at' => $today,
            'completed_at' => null,
        ]);

        // 2. 当日リマインダー対象
        ReadingPlan::create([
            'user_id' => 1,
            'book_id' => 2,
            'target_date' => $today,
            'status' => ReadingPlanStatus::Reading,
            'started_at' => $today,
            'completed_at' => null,
        ]);

        // 3. 期限3日超過
        // Auto-expire + 3日後再エンゲージメント確認用
        ReadingPlan::create([
            'user_id' => 1,
            'book_id' => 3,
            'target_date' => $today->copy()->subDays(3),
            'status' => ReadingPlanStatus::Reading,
            'started_at' => $today->copy()->subDays(10),
            'completed_at' => null,
        ]);

        // 4. リマインダー対象外
        ReadingPlan::create([
            'user_id' => 1,
            'book_id' => 4,
            'target_date' => $today->copy()->addDays(7),
            'status' => ReadingPlanStatus::Reading,
            'started_at' => $today,
            'completed_at' => null,
        ]);

        // 5. 完了済み
        ReadingPlan::create([
            'user_id' => 1,
            'book_id' => 5,
            'target_date' => $today->copy()->subDays(10),
            'status' => ReadingPlanStatus::Completed,
            'started_at' => $today->copy()->subDays(20),
            'completed_at' => $today->copy()->subDays(5),
        ]);

        // 鈴木花子：他ユーザー認可テスト用

        // 6. 山田太郎で /reading-plans/6/edit → 403
        ReadingPlan::create([
            'user_id' => 2,
            'book_id' => 6,
            'target_date' => $today->copy()->addDays(5),
            'status' => ReadingPlanStatus::Reading,
            'started_at' => $today,
            'completed_at' => null,
        ]);
    }
}