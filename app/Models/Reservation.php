<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $table = 'reservations';
    protected $guarded = [];
    protected $casts = [
        'date_check_in'  => 'date:Y-m-d',
        'date_check_out'  => 'date:Y-m-d',
    ];
    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id','id');
    }
    public function room() {
        return $this->belongsTo(Room::class, 'room_id','id');
    }
}
