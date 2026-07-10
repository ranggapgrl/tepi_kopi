<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = [
        'name', 'email', 'subject', 'message',
        'reply_message', 'replied_at', 'replied_by',
    ];

    protected function casts(): array
    {
        return [
            'read_at'    => 'datetime',
            'replied_at' => 'datetime',
        ];
    }

    public function repliedBy()
    {
        return $this->belongsTo(User::class, 'replied_by');
    }
}