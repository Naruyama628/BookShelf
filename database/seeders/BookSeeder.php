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
                'description' => '',
                'image_url' => ' https://placehold.co/200x300/e2e8f0/475569?text=1',
                'created_by' => User::first()->id,
                'genres' => ['小説'],
            ],
            [
                'title' => '人を動かす',
                'author' => 'D・カーネギー',
                'isbn' => '9784422100524',
                'published_date' => '1936-10-01',
                'description' => '',
                'image_url' => 'https://placehold.co/200x300/e2e8f0/475569?text=2',
                'created_by' => User::first()->id,
                'genres' => ['ビジネス', '自己啓発'],
            ],
            [
                'title' => 'リーダブルコード',
                'author' => 'Dustin Boswell',
                'isbn' => '9784873115658',
                'published_date' => '2012-06-23',
                'description' => '',
                'image_url' => 'https://placehold.co/200x300/e2e8f0/475569?text=3',
                'created_by' => User::first()->id,
                'genres' => ['技術書'],
            ],
            [
                'title' => '7つの習慣',
                'author' => 'スティーブン・R・コヴィー',
                'isbn' => '9784863940246',
                'published_date' => '2013-08-30',
                'description' => '',
                'image_url' => 'https://placehold.co/200x300/e2e8f0/475569?text=4',
                'created_by' => User::first()->id,
                'genres' => ['ビジネス', '自己啓発'],
            ],
            [
                'title' => '坊っちゃん',
                'author' => '夏目漱石',
                'isbn' => '9784101010021',
                'published_date' => '1906-04-01',
                'description' => '',
                'image_url' => 'https://placehold.co/200x300/e2e8f0/475569?text=5',
                'created_by' => User::first()->id,
                'genres' => ['小説'],
            ],
            [
                'title' => 'サピエンス全史',
                'author' => 'ユヴァル・ノア・ハラリ',
                'isbn' => '9784309226712',
                'published_date' => '2016-09-08',
                'description' => '',
                'image_url' => 'https://placehold.co/200x300/e2e8f0/475569?text=6',
                'created_by' => User::first()->id,
                'genres' => ['歴史', '科学'],
            ],
            [
                'title' => 'Clean Code',
                'author' => 'Robert C. Martin',
                'isbn' => '9784048930598',
                'published_date' => '2017-12-18',
                'description' => '',
                'image_url' => 'https://placehold.co/200x300/e2e8f0/475569?text=7',
                'created_by' => User::first()->id,
                'genres' => ['技術書'],
            ],
            [
                'title' => '嫌われる勇気',
                'author' => '岸見一郎・古賀史健',
                'isbn' => '9784478025819',
                'published_date' => '2013-12-13',
                'description' => '',
                'image_url' => 'https://placehold.co/200x300/e2e8f0/475569?text=8',
                'created_by' => User::first()->id,
                'genres' => ['自己啓発'],
            ],
            [
                'title' => '火花',
                'author' => '又吉直樹',
                'isbn' => '9784163902302',
                'published_date' => '2015-03-11',
                'description' => '',
                'image_url' => 'https://placehold.co/200x300/e2e8f0/475569?text=9',
                'created_by' => User::first()->id,
                'genres' => ['小説'],
            ],
            [
                'title' => 'FACTFULNESS',
                'author' => 'ハンス・ロスリング',
                'isbn' => '9784822289607',
                'published_date' => '2019-01-11',
                'description' => '',
                'image_url' => 'https://placehold.co/200x300/e2e8f0/475569?text=10',
                'created_by' => User::first()->id,
                'genres' => ['ビジネス', '科学'],
            ],
            [
                'title' => 'コンテナ物語',
                'author' => 'マルク・レビンソン',
                'isbn' => '9784822251468',
                'published_date' => '2007-01-18',
                'description' => '',
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
