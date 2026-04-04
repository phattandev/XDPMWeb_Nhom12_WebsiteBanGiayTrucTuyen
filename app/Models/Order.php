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

    public static function statusLabels(): array
    {
        return [
            'Pending' => 'Chờ duyệt',
            'Processing' => 'Đang xử lý',
            'Shipped' => 'Đang giao hàng',
            'Delivered' => 'Hoàn thành',
            'Cancelled' => 'Đã hủy',
        ];
    }

    public function getStatusLabelAttribute(): string
    {
        return static::statusLabels()[$this->status] ?? ($this->status ?: 'N/A');
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'Pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
            'Processing' => 'bg-blue-100 text-blue-800 border-blue-200',
            'Shipped' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
            'Delivered' => 'bg-green-100 text-green-800 border-green-200',
            'Cancelled' => 'bg-red-100 text-red-800 border-red-200',
            default => 'bg-slate-100 text-slate-700 border-slate-200',
        };
    }
}
