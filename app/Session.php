<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    protected $table = 'session';
    public $timestamps = false;
    protected $fillable = ['user_id', 'login_time', 'logout_time'];
    protected $hidden =  ['id'];
}
