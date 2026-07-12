<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = ['order_id', 'product_id', 'product_name', 'variant_id', 'quantity', 'price'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    /**
     * Nama produk yang aman ditampilkan meski produk aslinya sudah dihapus.
     * Utamakan nama produk yang masih hidup (bisa saja sudah di-update sejak
     * order dibuat), baru fallback ke snapshot product_name, baru fallback
     * ke teks generik kalau dua-duanya kosong (data lama sebelum kolom ini ada).
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->product->name ?? $this->product_name ?? 'Produk Dihapus';
    }
}