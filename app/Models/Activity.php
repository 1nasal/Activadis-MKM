<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    public function users()
    {
        return $this->belongsToMany(User::class, 'activity_user');
    }

    public function externals()
    {
        return $this->belongsToMany(External::class, 'activity_external');
    }
}
