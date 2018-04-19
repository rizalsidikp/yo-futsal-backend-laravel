<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'booking';

    protected $fillable = [
        'schedule_id', 'photo', 'status'
    ];

    public function schedule_detail()
    {
        return $this->belongsTo('App\Schedule', 'schedule_id', 'id');
    }
}
