<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'total_price', 'status',
        'shipping_address', 'shipping_phone', 'shipping_notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Kode order yang ditampilkan ke user, contoh: ORD-007.
     * Dipusatkan di sini biar kalau mau ganti format nanti
     * (misal ORD-20260705-007), tinggal ubah di satu tempat ini saja.
     */
    public function getOrderCodeAttribute(): string
    {
        return 'ORD-' . str_pad((string) $this->id, 3, '0', STR_PAD_LEFT);
    }
}