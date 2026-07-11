<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_tidak_bisa_menambah_produk_yang_tidak_ada(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/cart/add', [
            'product_id' => 99999,
            'quantity' => 1,
        ]);

        $response->assertSessionHasErrors('product_id');
        $this->assertDatabaseMissing('cart_items', ['product_id' => 99999]);
    }

    public function test_quantity_negatif_ditolak(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 10]);

        $response = $this->actingAs($user)->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => -5,
        ]);

        $response->assertSessionHasErrors('quantity');
        $this->assertDatabaseMissing('cart_items', ['product_id' => $product->id]);
    }

    public function test_quantity_nol_ditolak(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 10]);

        $response = $this->actingAs($user)->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 0,
        ]);

        $response->assertSessionHasErrors('quantity');
    }

    public function test_tidak_bisa_menambah_melebihi_stok_yang_tersedia(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 3]);

        $response = $this->actingAs($user)->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 5,
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('cart_items', ['product_id' => $product->id]);
    }

    public function test_variant_id_yang_bukan_milik_produk_ditolak(): void
    {
        $user = User::factory()->create();
        $productA = Product::factory()->create();
        $productB = Product::factory()->create();

        $variantOfB = ProductVariant::create([
            'product_id' => $productB->id,
            'name' => '250g',
            'price' => 50000,
            'stock' => 10,
        ]);

        // Coba "curang": product_id A tapi variant_id milik produk B
        $response = $this->actingAs($user)->post('/cart/add', [
            'product_id' => $productA->id,
            'variant_id' => $variantOfB->id,
            'quantity' => 1,
        ]);

        $response->assertSessionHas('error', 'Varian produk tidak valid.');
        $this->assertDatabaseMissing('cart_items', ['product_id' => $productA->id]);
    }

    public function test_menambah_produk_yang_sama_dua_kali_menumpuk_quantity_bukan_baris_baru(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 10]);

        $this->actingAs($user)->post('/cart/add', ['product_id' => $product->id, 'quantity' => 2]);
        $this->actingAs($user)->post('/cart/add', ['product_id' => $product->id, 'quantity' => 3]);

        $this->assertDatabaseCount('cart_items', 1);
        $this->assertDatabaseHas('cart_items', ['product_id' => $product->id, 'quantity' => 5]);
    }

    public function test_penambahan_bertahap_yang_melebihi_stok_ditolak(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 5]);

        $first = $this->actingAs($user)->post('/cart/add', ['product_id' => $product->id, 'quantity' => 4]);
        $first->assertSessionHas('success');

        // Total permintaan (4 + 4 = 8) melebihi stok (5), harus ditolak
        // dan quantity yang sudah ada di cart tidak boleh berubah.
        $second = $this->actingAs($user)->post('/cart/add', ['product_id' => $product->id, 'quantity' => 4]);
        $second->assertSessionHas('error');

        $this->assertDatabaseHas('cart_items', ['product_id' => $product->id, 'quantity' => 4]);
    }

    public function test_produk_dengan_stok_cukup_berhasil_ditambahkan(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 10]);

        $response = $this->actingAs($user)->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('cart_items', ['product_id' => $product->id, 'quantity' => 2]);
    }
}