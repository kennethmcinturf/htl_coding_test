<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function key()
    {
        return $this->belongsTo(Key::class);
    }

    public function technician()
    {
        return $this->belongsTo(Technician::class);
    }
}
