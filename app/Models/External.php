<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class External extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
    ];
    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'activity_external');
    }
}
