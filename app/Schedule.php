<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $table = 'schedule';

    protected $fillable = [
        'team_id', 'field_id', 'opponent_id', 'open_opponent', 'date', 'time', 'status'
    ];

    public function team_detail()
    {
        return $this->belongsTo('App\Team', 'team_id', 'id');
    }

    public function field_detail()
    {
        return $this->belongsTo('App\Field', 'field_id', 'id');
    }

    public function opponent_detail()
    {
        return $this->belongsTo('App\Team', 'opponent_id', 'id');
    }

    public function booking_detail()
    {
        return $this->belongsTo('App\Booking', 'id', 'schedule_id');
    }
}
