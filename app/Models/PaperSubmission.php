<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaperSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'conference_id',
        'conference_track_id',
        'user_id',
        'title',
        'abstract',
        'keywords',
        'full_paper_path',
        'presentation_file_path',
        'status',
        'validation_notes',
        'presentation_session_id',
        'is_best_paper',
        'payment_proof_path',
        'publication_status',
    ];

    public function reviews()
    {
        return $this->hasMany(PaperReview::class);
    }

    public function conference()
    {
        return $this->belongsTo(Conference::class);
    }

    public function track()
    {
        return $this->belongsTo(ConferenceTrack::class, 'conference_track_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function session()
    {
        return $this->belongsTo(PresentationSession::class, 'presentation_session_id');
    }
}
