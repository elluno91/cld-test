<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $table = 'rooms';
    protected $guarded = [];

    public function reservations() {
        return $this->hasMany(Reservation::class, 'room_id', 'id');
    }
}
