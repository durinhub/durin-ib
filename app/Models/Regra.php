<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Regra extends Model
{
    protected $table = 'regras';
    public $timestamps = false;
    
    public function board()
    {
        return $this->hasOne('App\Models\Board', 'sigla','board_name');
    }
}
