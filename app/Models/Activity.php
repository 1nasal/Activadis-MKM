<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = [
        'name',
        'location',
        'includes_food',
        'description',
        'start_time',
        'end_time',
        'cost',
        'max_participants',
        'min_participants',
        'image',
        'requirements',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'activity_user');
    }

    public function externals()
    {
        return $this->belongsToMany(External::class, 'activity_external');
    }
}