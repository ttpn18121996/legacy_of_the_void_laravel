<?php

namespace App\Enums;

enum ActressSort: string
{
    case THUMBNAIL = 'thumbnail';
    case TAGS = 'tags';
    case VIDEOS = 'videos';

    public static function labels(): array
    {
        return [
            self::THUMBNAIL->value => 'Thumbnail',
            self::TAGS->value => 'Tags',
            self::VIDEOS->value => 'Videos',
        ];
    }
}
