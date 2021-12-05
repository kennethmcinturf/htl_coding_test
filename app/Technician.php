<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Technician extends Model
{
    protected $appends = ['full_name'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function getFullNameAttribute()
    {
        return $this->last_name . ", " . $this->first_name;
    }
}
