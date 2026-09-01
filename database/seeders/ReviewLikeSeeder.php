<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Review;

class ReviewLikeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $reviews = Review::all();

        foreach ($reviews as $review) {

            // レビュー投稿者本人を除いたユーザーから
            // ランダムで0〜3人取得
            $userIds = \App\Models\User::where('id', '!=', $review->user_id)
                ->inRandomOrder()
                ->limit(rand(0, 3))
                ->pluck('id');

            $review->likedByUsers()
                ->syncWithoutDetaching($userIds);
        }
    }
}
