<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            // Nullable & nullOnDelete supaya log tetap ada walau akun admin
            // yang melakukan aksi itu kemudian dihapus dari sistem.
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('module', 50); // contoh: Produk, Kategori, Pesanan, User, Ulasan, Pesan Kontak
            $table->string('action', 20); // contoh: create, update, delete
            $table->text('description');  // contoh: Menghapus produk "Kopi Gayo Wine Process"
            $table->timestamp('created_at')->nullable();

            $table->index(['module']);
            $table->index(['created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};