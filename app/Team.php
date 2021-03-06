<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Team extends Model
{
    use SoftDeletes;
    public $incrementing = false;
    protected $table = 'team';

    protected $fillable = [
        'id', 'team_name', 'team_city', 'user_id'
    ];

    public function detail_team()
    {
        return $this->hasMany('App\DetailTeam', 'team_id', 'id');
    }
    
    protected $dates = ['deleted_at'];
}
