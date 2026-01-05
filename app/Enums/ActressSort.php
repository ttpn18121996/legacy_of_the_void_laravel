<?php

namespace App\Enums;

enum ActressSort: string
{
    case NOTHING = 'nothing';
    case THUMBNAIL = 'thumbnail';
    case TAGS = 'tags';
    case VIDEOS = 'videos';

    public static function labels(): array
    {
        return [
            self::NOTHING->value => 'Nothing',
            self::THUMBNAIL->value => 'Thumbnail',
            self::TAGS->value => 'Tags',
            self::VIDEOS->value => 'Videos',
        ];
    }
}
