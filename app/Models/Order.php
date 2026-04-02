<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    const CREATED_AT = 'order_date'; // Bảng dùng order_date thay vì created_at
    const UPDATED_AT = null;
    
    protected $fillable = ['user_id', 'total_amount', 'shipping_address', 'payment_method', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function details()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}