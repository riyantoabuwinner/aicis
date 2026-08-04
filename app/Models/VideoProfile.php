<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoProfile extends Model
{
    protected $fillable = [
        'title',
        'youtube_url',
        'is_active',
        'sort_order',
    ];
}
