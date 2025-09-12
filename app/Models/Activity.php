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
        'requirements'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'includes_food' => 'boolean',
        'cost' => 'decimal:2'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'activity_user')
               ->withTimestamps();
    }

    public function externals()
    {
        return $this->belongsToMany(External::class, 'activity_external');
    }
}