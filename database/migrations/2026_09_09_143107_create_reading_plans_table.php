<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reading_plans', function (Blueprint $table) {
            $table->id();

            // 計画者
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // 対象書籍
            $table->foreignId('book_id')
                ->constrained()
                ->cascadeOnDelete();

            // 期日
            $table->date('target_date');

            // 計画状態
            $table->enum('status', [
                'planned',
                'reading',
                'completed',
                'cancelled',
            ])->default('reading');

            // 関連する日時情報
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            // 作成日時・更新日時
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reading_plans');
    }
};
