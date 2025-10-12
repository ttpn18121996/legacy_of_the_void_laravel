<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    protected $fillable = ['title', 'slug'];

    public function actresses(): BelongsToMany
    {
        return $this->belongsToMany(Actress::class);
    }

    public function videos(): BelongsToMany
    {
        return $this->belongsToMany(Video::class);
    }

    public function getTitleForHumanAttribute()
    {
        return str($this->title)->title()->toString();
    }
}
