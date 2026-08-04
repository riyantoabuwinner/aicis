<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Counter extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'target_datetime',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'target_datetime' => 'datetime',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];
}
