<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public $timestamps = false; // Bảng này không có created_at, updated_at
    protected $fillable = ['name', 'description'];

    public function shoes()
    {
        return $this->hasMany(Shoe::class);
    }
}