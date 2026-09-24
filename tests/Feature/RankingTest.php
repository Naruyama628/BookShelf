<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Book;
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RankingTest extends TestCase
{
    use RefreshDatabase;

    public function test_ランキング画面を表示できる(): void
    {
        $response = $this->get(route('ranking.index'));

        $response->assertStatus(200);
        $response->assertViewIs('ranking.index');
        $response->assertViewHas('rankedBooks');
    }
}