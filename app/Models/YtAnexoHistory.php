<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YtAnexoHistory extends Ytanexo
{
    use HasFactory;
    protected $table = 'ytanexos_histories';
    public $timestamps = false;
    public $incrementing = false;
}
