<?php

namespace App\Support\Traits;

use Illuminate\Support\Facades\Storage;

trait HasFilePath
{
    public function getPublicPathAttribute()
    {
        $url = parse_url(Storage::disk('public')->url($this->getPath()));

        return $url['path'];
    }

    protected function getPath(): string
    {
        return $this->attributes['path'];
    }
}
