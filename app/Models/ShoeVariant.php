<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShoeVariant extends Model
{
    public $timestamps = false;
    protected $fillable = ['shoe_id', 'color', 'size', 'stock_quantity'];

    public function shoe()
    {
        return $this->belongsTo(Shoe::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
}