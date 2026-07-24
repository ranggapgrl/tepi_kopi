<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            // 'percentage' = potongan % dari subtotal, 'fixed' = potongan nominal rupiah tetap.
            $table->enum('type', ['percentage', 'fixed']);
            $table->unsignedInteger('value'); // persen (1-100) kalau 'percentage', rupiah kalau 'fixed'.
            $table->unsignedInteger('min_purchase')->default(0); // minimal subtotal supaya kupon berlaku.
            // Batas maksimal potongan rupiah untuk tipe 'percentage', supaya diskon 50% dari
            // belanja besar tidak menggerus margin tanpa batas. Tidak dipakai untuk tipe 'fixed'.
            $table->unsignedInteger('max_discount')->nullable();
            $table->unsignedInteger('usage_limit')->nullable(); // null = tidak terbatas.
            $table->unsignedInteger('used_count')->default(0);
            $table->timestamp('expires_at')->nullable(); // null = tidak kedaluwarsa.
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
