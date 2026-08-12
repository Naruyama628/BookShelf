<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Review;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $reviews = [
            // book_id = 1（3件）
            ['user_id' => 1, 'book_id' => 1, 'rating' => 5, 'comment' => '物語の語り口が独特で、最後まで楽しく読めました。'],
            ['user_id' => 2, 'book_id' => 1, 'rating' => 4, 'comment' => '登場人物の描写が面白く、印象に残る作品でした。'],
            ['user_id' => 3, 'book_id' => 1, 'rating' => 4, 'comment' => '時代を感じながらも、今読んでも楽しめる内容でした。'],

            // book_id = 2（3件）
            ['user_id' => 2, 'book_id' => 2, 'rating' => 5, 'comment' => '人間関係の描写が深く、考えさせられる作品でした。'],
            ['user_id' => 4, 'book_id' => 2, 'rating' => 4, 'comment' => '文章が読みやすく、物語に引き込まれました。'],
            ['user_id' => 5, 'book_id' => 2, 'rating' => 3, 'comment' => '少し難しい部分もありましたが、読んでよかったです。'],

            // book_id = 3（3件）
            ['user_id' => 1, 'book_id' => 3, 'rating' => 4, 'comment' => 'テンポがよく、一気に読み進めることができました。'],
            ['user_id' => 3, 'book_id' => 3, 'rating' => 5, 'comment' => '主人公の成長が丁寧に描かれていて面白かったです。'],
            ['user_id' => 5, 'book_id' => 3, 'rating' => 4, 'comment' => 'ユーモアのある場面が多く、楽しく読めました。'],

            // book_id = 4（3件）
            ['user_id' => 1, 'book_id' => 4, 'rating' => 5, 'comment' => '実践的な内容が多く、とても参考になりました。'],
            ['user_id' => 2, 'book_id' => 4, 'rating' => 4, 'comment' => '初心者にも分かりやすく説明されています。'],
            ['user_id' => 4, 'book_id' => 4, 'rating' => 5, 'comment' => '具体例が豊富で、理解しやすい内容でした。'],

            // book_id = 5（3件）
            ['user_id' => 2, 'book_id' => 5, 'rating' => 4, 'comment' => '仕事に活かせる考え方が多く紹介されていました。'],
            ['user_id' => 3, 'book_id' => 5, 'rating' => 3, 'comment' => '基本的な内容が中心ですが、復習にはよかったです。'],
            ['user_id' => 5, 'book_id' => 5, 'rating' => 5, 'comment' => 'すぐに実践できる内容が多く役立ちました。'],

            // book_id = 6（3件）
            ['user_id' => 1, 'book_id' => 6, 'rating' => 4, 'comment' => '歴史的な背景が分かりやすく整理されていました。'],
            ['user_id' => 4, 'book_id' => 6, 'rating' => 5, 'comment' => '知らなかった出来事も多く、とても勉強になりました。'],
            ['user_id' => 5, 'book_id' => 6, 'rating' => 4, 'comment' => '説明が丁寧で、歴史が苦手でも読みやすかったです。'],

            // book_id = 7（3件）
            ['user_id' => 2, 'book_id' => 7, 'rating' => 5, 'comment' => '科学の面白さを感じられる一冊でした。'],
            ['user_id' => 3, 'book_id' => 7, 'rating' => 4, 'comment' => '難しい内容を身近な例で説明していて分かりやすいです。'],
            ['user_id' => 4, 'book_id' => 7, 'rating' => 3, 'comment' => '専門的な部分もありますが、興味深く読めました。'],

            // book_id = 8（3件）
            ['user_id' => 1, 'book_id' => 8, 'rating' => 5, 'comment' => '作品の背景を知ることで芸術の見方が変わりました。'],
            ['user_id' => 3, 'book_id' => 8, 'rating' => 4, 'comment' => '写真や解説が分かりやすく、楽しみながら読めました。'],
            ['user_id' => 5, 'book_id' => 8, 'rating' => 4, 'comment' => '芸術に詳しくなくても楽しめる内容でした。'],

            // book_id = 9（3件）
            ['user_id' => 2, 'book_id' => 9, 'rating' => 4, 'comment' => '紹介されている料理を実際に作ってみたくなりました。'],
            ['user_id' => 4, 'book_id' => 9, 'rating' => 5, 'comment' => '手順が具体的で、初心者でも作りやすかったです。'],
            ['user_id' => 5, 'book_id' => 9, 'rating' => 4, 'comment' => '普段の料理に使えるアイデアがたくさんありました。'],

            // book_id = 10（2件）
            ['user_id' => 1, 'book_id' => 10, 'rating' => 5, 'comment' => '旅行先を選ぶ際の参考になる情報が豊富でした。'],
            ['user_id' => 3, 'book_id' => 10, 'rating' => 4, 'comment' => '読んでいるだけでも旅行した気分になれる本でした。'],

            // book_id = 11（3件）
            ['user_id' => 2, 'book_id' => 11, 'rating' => 5, 'comment' => '自分の考え方を見直すきっかけになる内容でした。'],
            ['user_id' => 4, 'book_id' => 11, 'rating' => 4, 'comment' => '具体的なアドバイスが多く、実践しやすいと思います。'],
            ['user_id' => 5, 'book_id' => 11, 'rating' => 3, 'comment' => '参考になる部分が多く、気軽に読み進められました。'],
        ];

        foreach ($reviews as $review) {
            Review::create($review);
        }
    }
}
