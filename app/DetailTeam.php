<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailTeam extends Model
{
    protected $table = 'detail_team';

    protected $fillable = [
        'team_id', 'user_id'
    ];

    public function user_detail()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }


    public function team_detail()
    {
        return $this->belongsTo('App\Team', 'team_id', 'id');
    }
}
