<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConferenceTrack extends Model
{
    use HasFactory;

    protected $fillable = [
        'conference_id',
        'name',
        'description',
    ];

    public function conference()
    {
        return $this->belongsTo(Conference::class);
    }

    public function submissions()
    {
        return $this->hasMany(PaperSubmission::class);
    }
}
