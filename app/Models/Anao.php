<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anao extends Model
{
    protected $table = 'anaos';
    protected $primaryKey = 'biscoito';
    public $incrementing = false;
    
    public function ips(){
        return $this->hasMany(AnaoIp::class,'biscoito','biscoito');
    }
    
    public function posts(){
        return $this->hasMany(Post::class,'biscoito','biscoito');
    }

    public function scopeIpAtual(): string{
        $ips = $this->ips()->orderBy('created_at', 'desc')->get();
        $first = $ips->first();
        return $first->ip;
    }
}
