<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Opponent extends Model
{
    protected $table = 'opponents';

    protected $fillable = [
        'team_id', 'opponent_id', 'status'
    ];
}
