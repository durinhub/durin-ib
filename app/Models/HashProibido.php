<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HashProibido extends Model
{
    //
    protected $table = 'hashes_proibidos';
    public $timestamps = false;
    protected $primaryKey = 'sha256';
    public $incrementing = false;
    
    protected $fillable = [
        'sha256'
    ];
    
}
