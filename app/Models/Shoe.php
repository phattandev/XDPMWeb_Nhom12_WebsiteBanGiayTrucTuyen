<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shoe extends Model
{
    const UPDATED_AT = null; // Bảng này chỉ có created_at
    protected $fillable = ['category_id', 'brand_id', 'name', 'description', 'price'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function images()
    {
        return $this->hasMany(ShoeImage::class);
    }

    public function variants()
    {
        return $this->hasMany(ShoeVariant::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}