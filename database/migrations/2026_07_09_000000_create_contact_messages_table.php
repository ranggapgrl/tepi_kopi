<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_messages', function (Blueprint $cols) {
            $cols->id();
            $cols->string('name');
            $cols->string('email');
            $cols->string('subject');
            $cols->text('message');
            $cols->timestamp('read_at')->nullable(); // buat admin nanti: tandai pesan sudah dibaca
            $cols->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
    }
};