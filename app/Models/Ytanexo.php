<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ytanexo extends Model
{
    protected $table = 'ytanexos';
    public $timestamps = false;
    protected $primaryKey = ['ytcode', 'post_id'];
    public $incrementing = false;
    
    protected $fillable = [
        'ytcode', 'post_id',
    ];
    
    public function post(){
        return $this->belongsTo('App\Models\Post');
    }
}
