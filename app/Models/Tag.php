<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['title', 'slug'];

    public function getTitleForHumanAttribute()
    {
        return str($this->title)->title()->toString();
    }
}
