<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $table = 'schedule';

    protected $fillable = [
        'team_id', 'field_id', 'opponent_id', 'open_opponent', 'date', 'time', 'status'
    ];
}
