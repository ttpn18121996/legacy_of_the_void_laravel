<?php

namespace App\Enums;

enum SearchType: string
{
    case ALL = '__all__';
    case ACTRESS = 'actress';
    case VIDEO = 'video';

    public function label(): string
    {
        return match ($this) {
            self::ALL => 'All',
            self::ACTRESS => 'Actress',
            self::VIDEO => 'Video',
        };
    }
}
