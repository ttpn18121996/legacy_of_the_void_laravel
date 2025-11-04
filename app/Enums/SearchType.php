<?php

namespace App\Enums;

enum SearchType: string
{
    case ACTRESS = 'actress';
    case VIDEO = 'video';

    public function label(): string
    {
        return match ($this) {
            self::ACTRESS => 'Actress',
            self::VIDEO => 'Video',
        };
    }
}
