<?php

namespace App\Enums;

enum SortDestination: string
{
    case ASCENDING = 'asc';
    case DESCENDING = 'desc';

    public static function labels(): array
    {
        return [
            self::ASCENDING->value => 'ASC',
            self::DESCENDING->value => 'DESC',
        ];
    }
}
