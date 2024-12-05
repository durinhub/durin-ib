<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ban extends Model
{
    protected $table = 'bans';
    public $timestamps = false;
    protected $dates = ['exp_date'];
    protected $primaryKey = ['ip', 'exp_date'];
    public $incrementing = false;
}
