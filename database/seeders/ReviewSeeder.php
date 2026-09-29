<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\User;
use App\Models\Book;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $books = Book::all();

        $comments = [
            '5' => ['素晴らしい本でした！', '人生が変わりました。', '何度も読み返しています。'],
            '4' => ['とても参考になりました。', '読みやすくておすすめです。。', '期待通りの内容でした。'],
            '3' => ['普通でした。', '可もなく不可もなく。', '期待したほどではなかった。'],
            '2' => ['少し期待外れでした。', '内容が薄い印象。', 'もう少し深掘りしてほしかった。'],
            '1' => ['残念ながら合いませんでした。', '期待と違いました。', '何度も読み返しています。'],
        ];

        foreach ($books as $book) {
            // 各書籍に2〜4件のレビュー
            $reviewCount = rand(2, 4);

            // 投稿者を重複なしでランダム選出
            $randomUsers = $users->random($reviewCount);

            foreach ($randomUsers as $user) {
                // 評価は1〜5
                $rating = rand(1, 5);

                Review::create([
                    'user_id' => $user->id,
                    'book_id' => $book->id,
                    'rating' => $rating,
                    'comment' => fake()->randomElement($comments[$rating]),
                ]);
            }
        }
    }
}
