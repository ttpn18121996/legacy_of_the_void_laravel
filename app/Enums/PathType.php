<?php

namespace App\Enums;

enum PathType: string
{
    case REVIEW = 'reviews';
    case APPROVED = 'approved';
    case MOVIE = 'movies';
    case TRASH = 'trash';

    public static function reviewable(): array
    {
        return [
            self::REVIEW->value,
            self::APPROVED->value,
            self::TRASH->value,
        ];
    }
}
