<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegionalDisease extends Model
{
    protected $fillable = [
        'name',
        'level',
        'distance',
        'trend',
        'is_active',
        'symptoms',
        'vector',
        'action_required',
    ];
}
