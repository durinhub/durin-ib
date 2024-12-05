<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLogHistory extends Model
{
    use HasFactory;
    protected $table = 'activity_log_histories';
    public $timestamps = false;
    public $incrementing = false;
}
