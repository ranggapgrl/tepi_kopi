<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExpireStaleOrdersTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_menunggu_pembayaran_yang_sudah_lama_dibatalkan_dan_stok_dikembalikan(): void
    {
        config(['tepikopi.unpaid_order_expiry_minutes' => 60]);

        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 5]);

        $order = Order::create([
            'user_id' => $user->id,
            'total_price' => $product->price * 2,
            'status' => 'Menunggu Pembayaran',
        ]);
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => $product->price,
        ]);

        // Simulasikan order dibuat 2 jam lalu (melebihi batas 60 menit).
        $order->created_at = now()->subHours(2);
        $order->save();

        $this->artisan('orders:expire-stale')->assertExitCode(0);

        $this->assertEquals('Dibatalkan', $order->fresh()->status);
        $this->assertEquals(7, $product->fresh()->stock); // 5 + 2 dikembalikan
    }

    public function test_order_yang_masih_baru_tidak_ikut_dibatalkan(): void
    {
        config(['tepikopi.unpaid_order_expiry_minutes' => 60]);

        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 5]);

        $order = Order::create([
            'user_id' => $user->id,
            'total_price' => $product->price,
            'status' => 'Menunggu Pembayaran',
        ]);
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => $product->price,
        ]);
        // created_at baru saja (default now()), belum melewati batas 60 menit.

        $this->artisan('orders:expire-stale')->assertExitCode(0);

        $this->assertEquals('Menunggu Pembayaran', $order->fresh()->status);
        $this->assertEquals(5, $product->fresh()->stock); // stok tidak berubah
    }

    public function test_order_yang_sudah_diproses_atau_selesai_tidak_ikut_dibatalkan(): void
    {
        config(['tepikopi.unpaid_order_expiry_minutes' => 60]);

        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 5]);

        $order = Order::create([
            'user_id' => $user->id,
            'total_price' => $product->price,
            'status' => 'Diproses', // sudah dibayar
        ]);
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => $product->price,
        ]);
        $order->created_at = now()->subHours(5);
        $order->save();

        $this->artisan('orders:expire-stale')->assertExitCode(0);

        $this->assertEquals('Diproses', $order->fresh()->status);
        $this->assertEquals(5, $product->fresh()->stock);
    }

    public function test_stok_varian_juga_dikembalikan_saat_order_kedaluwarsa(): void
    {
        config(['tepikopi.unpaid_order_expiry_minutes' => 60]);

        $user = User::factory()->create();
        $product = Product::factory()->create();
        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'name' => '250g',
            'price' => 60000,
            'stock' => 3,
        ]);

        $order = Order::create([
            'user_id' => $user->id,
            'total_price' => $variant->price * 2,
            'status' => 'Menunggu Pembayaran',
        ]);
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'variant_id' => $variant->id,
            'quantity' => 2,
            'price' => $variant->price,
        ]);
        $order->created_at = now()->subHours(3);
        $order->save();

        $this->artisan('orders:expire-stale')->assertExitCode(0);

        $this->assertEquals('Dibatalkan', $order->fresh()->status);
        $this->assertEquals(5, $variant->fresh()->stock); // 3 + 2 dikembalikan
    }
}