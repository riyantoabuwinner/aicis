<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaperReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'paper_submission_id',
        'reviewer_id',
        'status',
        'recommendation',
        'comments_for_author',
        'comments_for_admin',
        'deadline',
    ];

    protected function casts(): array
    {
        return [
            'deadline' => 'date',
        ];
    }

    public function paperSubmission()
    {
        return $this->belongsTo(PaperSubmission::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
}
