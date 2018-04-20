<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Opponent extends Model
{
    protected $table = 'opponents';

    protected $fillable = [
        'schedule_id' ,'team_id', 'opponent_id', 'type', 'status'
    ];

    public function schedule_detail()
    {
        return $this->belongsTo('App\Schedule', 'schedule_id', 'id');
    }

    public function team_detail()
    {
        return $this->belongsTo('App\Team', 'team_id', 'id');
    }

    public function opponent_detail()
    {
        return $this->belongsTo('App\Opponent', 'opponent_id', 'id');
    }
}
