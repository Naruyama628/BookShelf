<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\Genre;
use App\Models\User;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $books = [
            [
                'title' => '吾輩は猫である',
                'author' => '夏目漱石',
                'isbn' => '9784101010014',
                'published_date' => '1905-01-01',
                'description' => '猫の視点から人間社会や知識人たちの生活をユーモラスかつ風刺的に描いた夏目漱石の長編小説。',
                'image_url' => 'https://placehold.co/200x300/e2e8f0/475569?text=1',
                'created_by' => User::first()->id,
                'genres' => ['小説'],
            ],
            [
                'title' => '人を動かす',
                'author' => 'D・カーネギー',
                'isbn' => '9784422100524',
                'published_date' => '1936-10-01',
                'description' => '人間関係を円滑にし、相手との信頼関係を築くための考え方やコミュニケーションの原則を紹介した一冊。',
                'image_url' => 'https://placehold.co/200x300/e2e8f0/475569?text=2',
                'created_by' => User::first()->id,
                'genres' => ['ビジネス', '自己啓発'],
            ],
            [
                'title' => 'リーダブルコード',
                'author' => 'Dustin Boswell',
                'isbn' => '9784873115658',
                'published_date' => '2012-06-23',
                'description' => '他の人が理解しやすく、保守しやすいコードを書くための命名、コメント、制御フローなどの実践的な技法を解説した技術書。',
                'image_url' => 'https://placehold.co/200x300/e2e8f0/475569?text=3',
                'created_by' => User::first()->id,
                'genres' => ['技術書'],
            ],
            [
                'title' => '7つの習慣',
                'author' => 'スティーブン・R・コヴィー',
                'isbn' => '9784863940246',
                'published_date' => '2013-08-30',
                'description' => '主体性や目標設定、協力関係など、人生や仕事をより良いものにするための7つの習慣を体系的に紹介した一冊。',
                'image_url' => 'https://placehold.co/200x300/e2e8f0/475569?text=4',
                'created_by' => User::first()->id,
                'genres' => ['ビジネス', '自己啓発'],
            ],
            [
                'title' => '坊っちゃん',
                'author' => '夏目漱石',
                'isbn' => '9784101010021',
                'published_date' => '1906-04-01',
                'description' => '正義感が強く直情的な主人公が、地方の中学校教師として赴任し、学校内の人間関係や騒動に立ち向かう物語。',
                'image_url' => 'https://placehold.co/200x300/e2e8f0/475569?text=5',
                'created_by' => User::first()->id,
                'genres' => ['小説'],
            ],
            [
                'title' => 'サピエンス全史',
                'author' => 'ユヴァル・ノア・ハラリ',
                'isbn' => '9784309226712',
                'published_date' => '2016-09-08',
                'description' => 'ホモ・サピエンスがどのように社会を形成し、文明を発展させてきたのかを歴史や科学の視点から考察した一冊。',
                'image_url' => 'https://placehold.co/200x300/e2e8f0/475569?text=6',
                'created_by' => User::first()->id,
                'genres' => ['歴史', '科学'],
            ],
            [
                'title' => 'Clean Code',
                'author' => 'Robert C. Martin',
                'isbn' => '9784048930598',
                'published_date' => '2017-12-18',
                'description' => '読みやすく変更しやすいソフトウェアを作るために、クリーンなコードの原則や設計・リファクタリングの考え方を解説した技術書。',
                'image_url' => 'https://placehold.co/200x300/e2e8f0/475569?text=7',
                'created_by' => User::first()->id,
                'genres' => ['技術書'],
            ],
            [
                'title' => '嫌われる勇気',
                'author' => '岸見一郎・古賀史健',
                'isbn' => '9784478025819',
                'published_date' => '2013-12-13',
                'description' => 'アドラー心理学をもとに、他者の評価に縛られず自分らしく生きるための考え方を青年と哲人の対話形式で紹介した一冊。',
                'image_url' => 'https://placehold.co/200x300/e2e8f0/475569?text=8',
                'created_by' => User::first()->id,
                'genres' => ['自己啓発'],
            ],
            [
                'title' => '火花',
                'author' => '又吉直樹',
                'isbn' => '9784163902302',
                'published_date' => '2015-03-11',
                'description' => '若手芸人が先輩芸人との交流を通じて、笑いとは何か、生きるとは何かを模索していく姿を描いた小説。',
                'image_url' => 'https://placehold.co/200x300/e2e8f0/475569?text=9',
                'created_by' => User::first()->id,
                'genres' => ['小説'],
            ],
            [
                'title' => 'FACTFULNESS',
                'author' => 'ハンス・ロスリング',
                'isbn' => '9784822289607',
                'published_date' => '2019-01-11',
                'description' => '世界に対する思い込みをデータによって見直し、事実に基づいて物事を判断するための考え方を紹介した一冊。',
                'image_url' => 'https://placehold.co/200x300/e2e8f0/475569?text=10',
                'created_by' => User::first()->id,
                'genres' => ['ビジネス', '科学'],
            ],
            [
                'title' => 'コンテナ物語',
                'author' => 'マルク・レビンソン',
                'isbn' => '9784822251468',
                'published_date' => '2007-01-18',
                'description' => '海上輸送用コンテナの普及が物流を大きく変革し、世界経済や国際貿易に与えた影響を歴史的に描いた一冊。',
                'image_url' => 'https://placehold.co/200x300/e2e8f0/475569?text=11',
                'created_by' => User::first()->id,
                'genres' => ['ビジネス', '歴史'],
            ],
        ];

        foreach($books as $book)
        {
            $createdBook = Book::firstOrCreate(
                [
                    'isbn' => $book['isbn'],
                ],
                [
                    'title' => $book['title'],
                    'author' => $book['author'],
                    'published_date' => $book['published_date'],
                    'description' => $book['description'],
                    'image_url' => $book['image_url'],
                    'created_by' => $book['created_by'],
                ]
            );

            $genreIds = [];
            foreach($book['genres'] as $genre)
            {
                $genre = Genre::where('name', $genre)->first();
                if ($genre !== null) {
                    $genreIds[] = $genre->id;
                }
            }
            $createdBook->genres()->sync($genreIds);
        }
    }
}
