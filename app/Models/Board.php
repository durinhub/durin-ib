<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    protected $table = 'boards';
    protected $primaryKey = 'sigla';
    public $incrementing = false;
    public $timestamps = false;
    
    public function posts(){
        return $this->hasMany('App\Models\Post');
    }
    
    public function regras(){
        return $this->hasMany('App\Models\Regra','board_name');
    }
}
