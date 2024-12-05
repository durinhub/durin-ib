<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminRight extends Model
{
    use HasFactory;
    protected $table = 'admin_rights';
    public $timestamps = false;
    
    protected $fillable = [
        'user_id', 'right'
    ];
    
    public function users(){
        return $this->belongsToMany(User::class);
    }
}
