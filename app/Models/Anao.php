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

    public function ip(): string{
        return $this->ips()->orderBy('created_at', 'desc')->first()->ip;
    }
}
