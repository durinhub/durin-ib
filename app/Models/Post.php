<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'posts';
    
    public function arquivos(){
        return $this->hasMany('App\Models\Arquivo');
    }
    
    public function ytanexos(){
        return $this->hasMany('App\Models\Ytanexo');
    }
    
    public function reports(){
        return $this->hasMany('App\Models\Report');
    }
    
    public function anao()
    {
        return $this->hasOne('App\Models\Anao', 'biscoito', 'biscoito');
    }
    
    public function ban()
    {
        return $this->hasOne('App\Models\Ban');
    }
    
    public function board()
    {
        return $this->hasOne('App\Models\Board', 'sigla','board');
    }
    
}
