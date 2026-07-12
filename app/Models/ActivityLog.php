<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class ActivityLog extends Model
{
    public $timestamps = false; // cuma pakai created_at, nggak butuh updated_at

    protected $fillable = ['user_id', 'module', 'action', 'description', 'created_at'];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Helper singkat buat dipanggil dari controller mana pun:
     * ActivityLog::record('Produk', 'create', 'Menambahkan produk "Kopi Gayo".');
     */
    public static function record(string $module, string $action, string $description): void
    {
        static::create([
            'user_id'    => Auth::id(),
            'module'     => $module,
            'action'     => $action,
            'description' => $description,
            'created_at' => now(),
        ]);
    }
}