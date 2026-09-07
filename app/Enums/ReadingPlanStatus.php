<?php

namespace App\Enums;

enum ReadingPlanStatus: string
{
    case PLANNED = 'planned';
    case READING = 'reading';
    case COMPLETED = 'completed';
    
    public function label(): string
    {
        return match ($this) {
            self::PLANNED => '読書予定',
            self::READING => '読書中',
            self::COMPLETED => '読了',
        };
    }
}