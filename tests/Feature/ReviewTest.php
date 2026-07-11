<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_tidak_bisa_review_produk_yang_belum_pernah_dibeli(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($user)->post(route('reviews.store', $product), [
            'rating' => 5,
            'comment' => 'Enak banget',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseCount('reviews', 0);
    }

    public function test_user_tidak_bisa_review_kalau_pesanan_belum_selesai(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $order = Order::create([
            'user_id' => $user->id,
            'total_price' => $product->price,
            'status' => 'Diproses',
        ]);
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => $product->price,
        ]);

        $response = $this->actingAs($user)->post(route('reviews.store', $product), [
            'rating' => 5,
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseCount('reviews', 0);
    }

    public function test_user_bisa_review_produk_yang_sudah_dibeli_dan_selesai(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $order = Order::create([
            'user_id' => $user->id,
            'total_price' => $product->price,
            'status' => 'Selesai',
        ]);
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => $product->price,
        ]);

        $response = $this->actingAs($user)->post(route('reviews.store', $product), [
            'rating' => 4,
            'comment' => 'Mantap',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('reviews', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'rating' => 4,
        ]);
    }

    public function test_user_tidak_bisa_review_produk_yang_sama_dua_kali(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $order = Order::create([
            'user_id' => $user->id,
            'total_price' => $product->price,
            'status' => 'Selesai',
        ]);
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => $product->price,
        ]);

        $this->actingAs($user)->post(route('reviews.store', $product), ['rating' => 5]);
        $response = $this->actingAs($user)->post(route('reviews.store', $product), ['rating' => 3]);

        $response->assertSessionHas('error');
        $this->assertDatabaseCount('reviews', 1);
    }
}