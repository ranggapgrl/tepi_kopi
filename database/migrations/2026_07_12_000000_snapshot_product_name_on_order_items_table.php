<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * BUGFIX: sebelumnya order_items.product_id pakai cascadeOnDelete(). Kalau
 * produk yang sudah pernah dipesan dihapus dari database (lewat controller
 * ada guard yang mencegah ini, tapi lewat query manual / tinker / DB client
 * tetap bisa terjadi), SEMUA baris order_items yang mereferensikannya ikut
 * kehapus otomatis oleh MySQL — riwayat pesanan customer (termasuk yang
 * sudah "Selesai") jadi rusak/hilang tanpa jejak.
 *
 * Sekarang:
 * 1. order_items.product_name menyimpan snapshot nama produk saat pesanan
 *    dibuat, jadi riwayat pesanan tetap terbaca walau produk aslinya sudah
 *    tidak ada lagi.
 * 2. product_id diubah jadi nullable + nullOnDelete() (bukan cascadeOnDelete),
 *    konsisten dengan variant_id yang sudah lebih dulu pakai pendekatan ini
 *    (lihat migration add_variant_id_to_order_items_table). Baris order_items
 *    tidak akan pernah ikut terhapus lagi gara-gara produknya dihapus.
 *
 * Catatan: pakai subquery UPDATE dan Blueprint::change() (bukan raw
 * "ALTER ... MODIFY" ala MySQL) supaya migration ini portable dan tetap bisa
 * jalan di SQLite in-memory yang dipakai phpunit.xml saat `php artisan test`.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->string('product_name')->nullable()->after('product_id');
        });

        // Backfill: salin nama produk yang saat ini masih ada ke kolom
        // snapshot, supaya data lama juga langsung punya nama meski belum
        // pernah "snapshot" saat dibuat.
        DB::statement('
            UPDATE order_items
            SET product_name = (SELECT name FROM products WHERE products.id = order_items.product_id)
            WHERE product_name IS NULL AND product_id IS NOT NULL
        ');

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->nullable()->change();
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->nullable(false)->change();
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('product_name');
        });
    }
};