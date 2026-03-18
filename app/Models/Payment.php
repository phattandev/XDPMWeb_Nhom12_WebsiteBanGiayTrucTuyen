<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    const CREATED_AT = 'payment_date'; // Dùng payment_date thay cho created_at
    const UPDATED_AT = null;

    protected $fillable = [
        'order_id', 
        'transaction_id', 
        'amount', 
        'payment_method', 
        'payment_status'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}