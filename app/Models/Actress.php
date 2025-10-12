<?php

namespace App\Models;

use App\Support\Traits\HasFilePath;
use App\Support\Traits\HasHighlightTitle;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Actress extends Model
{
    use HasFilePath, HasHighlightTitle;

    protected $fillable = [
        'name',
        'another_name',
        'thumbnail_path',
        'created_at',
        'updated_at',
    ];

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    protected function getPath(): string
    {
        return $this->attributes['thumbnail_path'] ?? 'actresses/thumbnail-default.jpg';
    }
}
