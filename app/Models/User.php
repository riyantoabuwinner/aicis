<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\HasName;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable implements FilamentUser, HasAvatar, HasName
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'gender',
        'avatar_url',
        'front_title',
        'back_title',
        'email',
        'whatsapp_number',
        'address',
        'province',
        'city',
        'postal_code',
        'highest_education',
        'study_program',
        'university',
        'institution',
        'password',
        'is_approved',
    ];

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar_url ? Storage::url($this->avatar_url) : null;
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_approved' => 'boolean',
        ];
    }
    
    public function paperSubmissions()
    {
        return $this->hasMany(PaperSubmission::class);
    }

    public function presentationSessions()
    {
        return $this->hasMany(PresentationSession::class, 'moderator_id');
    }

    public function assignedReviews()
    {
        return $this->hasMany(PaperReview::class, 'reviewer_id');
    }

    public function canAccessPanel(Panel $panel): bool
    {
        // Allow all registered users to access the dashboard.
        // We will protect specific resources using Spatie roles.
        return true;
    }

    public function getFilamentName(): string
    {
        $parts = [];
        if (!empty($this->front_title)) $parts[] = trim($this->front_title);
        $parts[] = trim($this->name);
        
        $fullName = implode(' ', $parts);
        if (!empty($this->back_title)) {
            $fullName .= ', ' . trim($this->back_title);
        }
        
        return $fullName;
    }
}
