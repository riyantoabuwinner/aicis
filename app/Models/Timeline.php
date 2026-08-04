<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timeline extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'date_from',
        'date_until',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'date_from' => 'date',
        'date_until' => 'date',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];
}
