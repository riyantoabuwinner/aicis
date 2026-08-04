<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conference extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'theme',
        'start_date',
        'end_date',
        'venue',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function tracks()
    {
        return $this->hasMany(ConferenceTrack::class);
    }

    public function sessions()
    {
        return $this->hasMany(PresentationSession::class);
    }

    public function submissions()
    {
        return $this->hasMany(PaperSubmission::class);
    }
}
