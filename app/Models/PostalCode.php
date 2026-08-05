<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostalCode extends Model
{
    use HasFactory;

    protected $fillable = ['city_id', 'postal_code'];

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
