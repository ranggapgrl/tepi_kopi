<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AutoCompleteShippedOrdersTest extends TestCase
{
    use RefreshDatabase;

    public function test_pesanan_dikirim_yang_sudah_lama_diselesaikan_otomatis(): void
    {
        config(['tepikopi.auto_complete_shipped_after_days' => 7]);

        $user = User::factory()->create();
        $product = Product::factory()->create();

        $order = Order::create([
            'user_id' => $user->id,
            'total_price' => $product->price,
            'status' => 'Dikirim',
            'shipped_at' => now()->subDays(10),
        ]);
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => $product->price,
        ]);

        $this->artisan('orders:auto-complete')->assertExitCode(0);

        $order->refresh();
        $this->assertEquals('Selesai', $order->status);
        $this->assertNotNull($order->completed_at);
    }

    public function test_pesanan_dikirim_yang_masih_baru_tidak_ikut_diselesaikan(): void
    {
        config(['tepikopi.auto_complete_shipped_after_days' => 7]);

        $user = User::factory()->create();
        $product = Product::factory()->create();

        $order = Order::create([
            'user_id' => $user->id,
            'total_price' => $product->price,
            'status' => 'Dikirim',
            'shipped_at' => now()->subDays(2), // belum melewati batas 7 hari
        ]);
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => $product->price,
        ]);

        $this->artisan('orders:auto-complete')->assertExitCode(0);

        $this->assertEquals('Dikirim', $order->fresh()->status);
    }

    public function test_pesanan_status_lain_tidak_ikut_diselesaikan(): void
    {
        config(['tepikopi.auto_complete_shipped_after_days' => 7]);

        $user = User::factory()->create();
        $product = Product::factory()->create();

        $order = Order::create([
            'user_id' => $user->id,
            'total_price' => $product->price,
            'status' => 'Diproses',
            'shipped_at' => null,
        ]);
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => $product->price,
        ]);

        $this->artisan('orders:auto-complete')->assertExitCode(0);

        $this->assertEquals('Diproses', $order->fresh()->status);
    }

    public function test_pesanan_dikirim_yang_sudah_dikonfirmasi_customer_tidak_ikut_diselesaikan_lagi(): void
    {
        config(['tepikopi.auto_complete_shipped_after_days' => 7]);

        $user = User::factory()->create();
        $product = Product::factory()->create();

        // Sudah dikonfirmasi customer lebih dulu sebelum command jalan.
        $order = Order::create([
            'user_id' => $user->id,
            'total_price' => $product->price,
            'status' => 'Selesai',
            'shipped_at' => now()->subDays(10),
            'completed_at' => now()->subDay(),
        ]);

        $this->artisan('orders:auto-complete')->assertExitCode(0);

        $this->assertEquals('Selesai', $order->fresh()->status);
    }
}