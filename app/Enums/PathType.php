<?php

namespace App\Enums;

enum PathType: string
{
    case REVIEW = 'reviews';
    case APPROVED = 'approved';
    case MOVIE = 'movies';

    public static function reviewable(): array
    {
        return [
            self::REVIEW->value,
            self::APPROVED->value,
        ];
    }
}
