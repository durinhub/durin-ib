<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DadoMapaDePosts extends Model
{
    //
    protected $table = 'dados_mapa_de_posts';
    protected $primaryKey = 'ip';
    public $timestamps = false;
    public $incrementing = false;
}
