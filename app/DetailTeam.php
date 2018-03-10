<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailTeam extends Model
{
    protected $table = 'detail_team';

    protected $fillable = [
        'team_id', 'user_id'
    ];
}
