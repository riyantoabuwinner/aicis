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
        'until_date',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'target_datetime' => 'datetime',
        'until_date' => 'datetime',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];
}
