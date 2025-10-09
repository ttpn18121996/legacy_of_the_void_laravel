<?php

namespace App\Enums;

enum SortDestination: string
{
    case ASCENDING = 'asc';
    case DESCENDING = 'desc';

    public static function labels(): array
    {
        return [
            self::ASCENDING->value => 'Ascending',
            self::DESCENDING->value => 'Descending',
        ];
    }
}
