<?php
// app/Models/Activity.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
        'image', // Behoud het oude image veld voor backward compatibility
        'requirements'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'includes_food' => 'boolean',
        'cost' => 'decimal:2'
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'activity_user')
               ->withTimestamps();
    }

    public function externals(): BelongsToMany
    {
        return $this->belongsToMany(External::class, 'activity_external');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ActivityImage::class)->orderBy('sort_order');
    }

    public function getMainImageAttribute(): ?ActivityImage
    {
        return $this->images()->first();
    }

    // Helper method to get the primary image (either from images relation or old image field)
    public function getPrimaryImageAttribute(): ?string
    {
        // Check if there are uploaded images first
        if ($this->images->count() > 0) {
            return $this->images->first()->url;
        }
        
        // Fall back to the old image field if it exists
        return $this->image;
    }
}