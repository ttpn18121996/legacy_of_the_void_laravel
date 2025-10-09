<?php

namespace App\Enums;

enum VideoSort: string
{
    case RECENTLY_ADDED = 'recently-added';
    case MOST_LIKED = 'most-liked';

    public static function labels(): array
    {
        return [
            self::RECENTLY_ADDED->value => 'Recently added',
            self::MOST_LIKED->value => 'Most liked',
        ];
    }
}
