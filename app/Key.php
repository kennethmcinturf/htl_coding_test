<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Key extends Model
{
    protected $appends = ['vehicle_info'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function vehicleKey()
    {
        return $this->hasOne(VehicleKey::class);
    }

    public function vehicle() 
    {
        return $this->hasManyThrough(Vehicle::class, VehicleKey::class, 'vehicle_id', 'id');
    }

    public function getVehicleInfoAttribute()
    {
        $vehicle = $this->vehicle->first();
        $keyInfo = 'Key #'.$this->id.' - ';

        if (!$vehicle) {
            return $keyInfo.'. No Vehicle Assigned';
        }

        return $keyInfo.$vehicle->make. " - " .$vehicle->model." - ".$vehicle->vin;
    }
}
