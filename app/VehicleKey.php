<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VehicleKey extends Model
{
    public function key()
    {
        return $this->belongsTo(Key::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
