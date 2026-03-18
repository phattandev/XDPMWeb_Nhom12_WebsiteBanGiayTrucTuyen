<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShoeImage extends Model
{
    public $timestamps = false;
    protected $fillable = ['shoe_id', 'image_url', 'is_primary'];

    public function shoe()
    {
        return $this->belongsTo(Shoe::class);
    }
}