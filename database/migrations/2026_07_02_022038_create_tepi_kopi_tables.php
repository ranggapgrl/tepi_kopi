<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Buat Tabel Induk Paling Awal
        Schema::create('categories', function (Blueprint $cols) {
            $cols->id();
            $cols->string('name');
            $cols->timestamps();
        });

        // 2. Buat Tabel Produk
        Schema::create('products', function (Blueprint $cols) {
            $cols->id();
            $cols->foreignId('category_id')->constrained()->cascadeOnDelete();
            $cols->string('name');
            $cols->text('description')->nullable();
            $cols->integer('price');
            $cols->integer('stock')->default(0);
            $cols->string('image')->nullable();
            $cols->timestamps();
        });

        // 3. Buat Tabel Keranjang
        Schema::create('carts', function (Blueprint $cols) {
            $cols->id();
            $cols->unsignedBigInteger('user_id')->default(1);
            $cols->timestamps();
        });

        // 4. Buat Tabel Item Keranjang (Sekarang carts dan products sudah siap!)
        Schema::create('cart_items', function (Blueprint $cols) {
            $cols->id();
            $cols->foreignId('cart_id')->constrained()->cascadeOnDelete();
            $cols->foreignId('product_id')->constrained()->cascadeOnDelete();
            $cols->integer('quantity')->default(1);
            $cols->timestamps();
        });

        // 5. Buat Tabel Pesanan Utama
        Schema::create('orders', function (Blueprint $cols) {
            $cols->id();
            $cols->unsignedBigInteger('user_id')->default(1);
            $cols->integer('total_price');
            $cols->string('status')->default('Menunggu Pembayaran');
            $cols->timestamps();
        });

        // 6. Buat Tabel Rincian Pesanan
        Schema::create('order_items', function (Blueprint $cols) {
            $cols->id();
            $cols->foreignId('order_id')->constrained()->cascadeOnDelete();
            $cols->foreignId('product_id')->constrained()->cascadeOnDelete();
            $cols->integer('quantity');
            $cols->integer('price');
            $cols->timestamps();
        });
    }

    public function down(): void
    {
        // Drop tabel dari anak ke induk (kebalikan dari up)
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('carts');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
    }
};