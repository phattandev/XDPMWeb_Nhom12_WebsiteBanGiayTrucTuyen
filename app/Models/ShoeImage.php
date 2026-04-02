<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShoeImage extends Model
{
    protected $fillable = [
        'shoe_id', 
        'image_url', 
        'public_id', 
        'is_primary'
    ];

    public $timestamps = false;

    public function shoe()
    {
        return $this->belongsTo(Shoe::class);
    }
}