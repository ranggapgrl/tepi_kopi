<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'name', 'description', 'price', 'image', 'stock'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    public function images()
{
    return $this->hasMany(ProductImage::class)->orderBy('sort_order');
}

public function variants()
{
    return $this->hasMany(ProductVariant::class)->orderBy('sort_order');
}

public function reviews()
{
    return $this->hasMany(Review::class);
}
}