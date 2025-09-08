<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class External extends Model
{
    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'activity_external');
    }
}
