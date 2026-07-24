<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code', 'type', 'value', 'min_purchase',
        'max_discount', 'usage_limit', 'used_count',
        'expires_at', 'is_active',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Validasi kupon terhadap subtotal belanja tertentu.
     * Return null kalau valid, atau pesan error kalau tidak.
     */
    public function errorForSubtotal(int $subtotal): ?string
    {
        if (! $this->is_active) {
            return 'Kupon ini sudah tidak aktif.';
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return 'Kupon ini sudah kedaluwarsa.';
        }

        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit) {
            return 'Kupon ini sudah mencapai batas pemakaian.';
        }

        if ($subtotal < $this->min_purchase) {
            return 'Minimal belanja untuk kupon ini adalah Rp ' . number_format($this->min_purchase, 0, ',', '.') . '.';
        }

        return null;
    }

    /**
     * Hitung nominal potongan (rupiah, sudah dibulatkan) untuk subtotal tertentu.
     * Dipanggil HANYA setelah errorForSubtotal() memastikan kupon valid.
     */
    public function calculateDiscount(int $subtotal): int
    {
        if ($this->type === 'percentage') {
            $discount = (int) round($subtotal * ($this->value / 100));

            if ($this->max_discount !== null) {
                $discount = min($discount, $this->max_discount);
            }
        } else {
            $discount = $this->value;
        }

        // Diskon tidak boleh melebihi subtotal (mis. kupon fixed Rp 50rb
        // dipakai untuk belanja Rp 20rb).
        return min($discount, $subtotal);
    }
}
