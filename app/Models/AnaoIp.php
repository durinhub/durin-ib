<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnaoIp extends Model
{
    use HasFactory;
    protected $table = 'anao_ips';
    protected $primaryKey = ['biscoito','ip'];
    public $incrementing = false;
    
    public function anaos(){
        return $this->belongsToMany(Anao::class,'anao_ips', 'biscoito', 'biscoito');
    }
}
