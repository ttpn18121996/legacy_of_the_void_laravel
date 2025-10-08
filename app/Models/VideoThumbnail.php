<?php

namespace App\Models;

use App\Support\Traits\HasFilePath;
use Illuminate\Database\Eloquent\Model;

class VideoThumbnail extends Model
{
    use HasFilePath;

    protected $guarded = [];
}
