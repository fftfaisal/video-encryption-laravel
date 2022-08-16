<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        'convert_start_for_streaming_at'    => 'datetime',
        'converted_for_streaming_at'        => 'datetime',
    ];

    public function url() : Attribute
    {
        return Attribute::get( fn ($value) => url($this->id.'.m3u8'));
    }
}
