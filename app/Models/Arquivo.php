<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Arquivo extends Model
{
    public $timestamps = false;
    protected $table = 'arquivos';
    protected $primaryKey = 'filename';
    public $incrementing = false;
    
    protected $fillable = [
        'filename', 'mime', 'spoiler', 'original_filename', 'filesize', 'thumb'
    ];
    
    public function post(){
        return $this->belongsTo('App\Models\Post');
    }
}
