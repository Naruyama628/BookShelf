<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class FavoriteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $user = User::find(1);
        $user->favoriteBooks()->syncWithoutDetaching([1, 2, 3]);

        $user = User::find(2);
        $user->favoriteBooks()->syncWithoutDetaching([4, 5, 6, 7]);
        
        $user = User::find(3);
        $user->favoriteBooks()->syncWithoutDetaching([7, 8, 9 , 10]);

        $user = User::find(4);
        $user->favoriteBooks()->syncWithoutDetaching([10, 11, 3]);

        $user = User::find(5);
        $user->favoriteBooks()->syncWithoutDetaching([4, 8, 10, 11]);

    }
}
