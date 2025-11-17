<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $fillable = [
        'filename',
        'processed_filename',
        'status',
        'details',
        'user_id',
    ];
}
