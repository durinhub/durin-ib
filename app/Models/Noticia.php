<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Noticia extends Model
{
    protected $table = 'noticias';
    
    public function autor()
    {
        return $this->hasOne('App\Models\User', 'id','autor_id');
    }
    
}
