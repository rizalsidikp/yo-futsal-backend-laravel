<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Field extends Model
{

    protected $table = 'field';

    protected $fillable = [
        'user_id', 'name', 'email', 'phone_number', 'city', 'address', 'description', 'photo'
    ];

    public function user_detail()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
