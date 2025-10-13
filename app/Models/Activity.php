<?php
// app/Models/Activity.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
        'image', // backward compatibility
        'requirements',
    ];

    protected $casts = [
        'start_time'    => 'datetime',
        'end_time'      => 'datetime',
        'includes_food' => 'boolean',
        'cost'          => 'decimal:2',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'activity_user')->withTimestamps();
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

    /**
     * Primary image URL accessor (uploads -> legacy -> external URL placeholder)
     * Usage in Blade: {{ $activity->primary_image_url }}
     */
    public function getPrimaryImageUrlAttribute(): string
    {
        // 1) First uploaded image (stored on 'public' disk)
        $first = $this->images->first();
        if ($first && !empty($first->path)) {
            return Storage::disk('public')->url($first->path);
        }

        // 2) Legacy 'image' field
        if (!empty($this->image)) {
            // Already an absolute URL
            if (Str::startsWith($this->image, ['http://', 'https://'])) {
                return $this->image;
            }

            // A file stored on 'public' disk
            if (Storage::disk('public')->exists($this->image)) {
                return Storage::disk('public')->url($this->image);
            }

            // A path relative to /public (asset) -> still a full URL
            return asset(ltrim($this->image, '/'));
        }

        // 3) Always return an external URL placeholder as the final fallback
        // You can customize text/size if you want.
        return 'https://placehold.co/600x400?text=Activiteit';
    }
}
