<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlacklistedString extends Model
{
    //
    protected $table = 'blacklisted_strings';
    public $timestamps = false;
    protected $fillable = [
        'conteudo'
    ];
}
