<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    public $timestamps = false;
    protected $fillable = ['name'];

    public function shoes()
    {
        return $this->hasMany(Shoe::class);
    }
}