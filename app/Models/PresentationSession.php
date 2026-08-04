<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresentationSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'conference_id',
        'name',
        'room',
        'date',
        'start_time',
        'end_time',
        'moderator_id',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function conference()
    {
        return $this->belongsTo(Conference::class);
    }

    public function moderator()
    {
        return $this->belongsTo(User::class, 'moderator_id');
    }

    public function submissions()
    {
        return $this->hasMany(PaperSubmission::class);
    }
}
