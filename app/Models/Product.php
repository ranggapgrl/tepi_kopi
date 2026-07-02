<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'image',
        'stock'
    ];

    // Relasi balik: Satu produk hanya memiliki satu kategori
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}