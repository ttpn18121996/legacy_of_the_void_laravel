<?php

namespace App\Models;

use App\Support\Traits\HasFilePath;
use App\Support\Traits\HasHighlightTitle;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Video extends Model
{
    use HasFilePath, HasHighlightTitle;

    protected $fillable = [
        'title',
        'path',
        'duration',
        'dimensions',
        'created_at',
        'updated_at',
        'like',
        'latest_like',
    ];

    public function thumbnails(): HasMany
    {
        return $this->hasMany(VideoThumbnail::class);
    }

    public function thumbnail(): HasOne
    {
        return $this->hasOne(VideoThumbnail::class)->where('is_default', true);
    }

    public function actresses(): BelongsToMany
    {
        return $this->belongsToMany(Actress::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function getQualityAttribute()
    {
        if (str($this->dimensions)->endsWith('x 720')) {
            return '720p';
        } elseif (str($this->dimensions)->endsWith('x 1080') || str($this->dimensions)->startsWith('1920 x')) {
            return 'HD';
        } elseif (str($this->dimensions)->endsWith('x 1440')) {
            return '2K';
        } else {
            return $this->dimensions;
        }
    }

    public function isHD(): bool
    {
        return in_array($this->getQualityAttribute(), ['HD', '2K']);
    }
}
