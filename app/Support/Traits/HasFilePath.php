<?php

namespace App\Support\Traits;

use Illuminate\Support\Facades\Storage;

trait HasFilePath
{
    public function getPublicPathAttribute()
    {
        /** @var \Illuminate\Filesystem\FilesystemAdapter $fileSystem */
        $fileSystem = Storage::disk('public');
        $url = parse_url($fileSystem->url($this->getPath()));

        return $url['path'];
    }

    protected function getPath(): string
    {
        return $this->attributes['path'];
    }
}
