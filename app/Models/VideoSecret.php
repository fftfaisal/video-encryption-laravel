<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoSecret extends Model
{
    use HasFactory;

    protected $table = 'hls_secrets';

    protected $guarded = [];
}
