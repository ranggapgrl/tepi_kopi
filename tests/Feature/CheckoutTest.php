<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkout_mengurangi_stok_produk_dan_membuat_order(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 10, 'price' => 50000]);

        $cart = Cart::create(['user_id' => $user->id]);
        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $response = $this->actingAs($user)->post('/checkout', [
            'shipping_address' => 'Jl. Contoh No. 1',
            'shipping_phone' => '081234567890',
        ]);

        $response->assertRedirect();
        $response->assertSessionDoesntHaveErrors();

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'status' => 'Menunggu Pembayaran',
        ]);

        $this->assertEquals(8, $product->fresh()->stock);

        // Keranjang harus sudah kosong setelah checkout berhasil.
        $this->assertEquals(0, CartItem::where('cart_id', $cart->id)->count());
    }

    public function test_checkout_gagal_kalau_stok_tidak_cukup(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 1]);

        $cart = Cart::create(['user_id' => $user->id]);
        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 5,
        ]);

        $response = $this->actingAs($user)->post('/checkout', [
            'shipping_address' => 'Jl. Contoh No. 1',
            'shipping_phone' => '081234567890',
        ]);

        $response->assertRedirect('/cart');
        $response->assertSessionHas('error');

        // Stok tidak boleh berubah dan tidak ada order yang terbentuk.
        $this->assertEquals(1, $product->fresh()->stock);
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_checkout_kosong_redirect_ke_cart(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/checkout', [
            'shipping_address' => 'Jl. Contoh No. 1',
            'shipping_phone' => '081234567890',
        ]);

        $response->assertRedirect('/cart');
        $response->assertSessionHas('error');
    }
}