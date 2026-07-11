<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BugFixesTest extends TestCase
{
    use RefreshDatabase;

    public function test_halaman_yang_sebelumnya_kena_bug_case_sensitivity_bisa_diakses(): void
    {
        $this->get('/')->assertOk();
        $this->get('/katalog')->assertOk();
        $this->get('/contact')->assertOk();
        $this->get('/about')->assertOk();

        $admin = User::factory()->create();
        $admin->role = 'admin';
        $admin->save();

        $this->actingAs($admin)->get('/admin')->assertOk();
        $this->actingAs($admin)->get(route('users.index'))->assertOk();
        $this->actingAs($admin)->get(route('users.create'))->assertOk();
    }

    public function test_produk_yang_punya_riwayat_pesanan_tidak_bisa_dihapus(): void
    {
        $admin = User::factory()->create();
        $admin->role = 'admin';
        $admin->save();

        $product = Product::factory()->create();
        $order = Order::create([
            'user_id' => $admin->id,
            'total_price' => $product->price,
            'status' => 'Selesai',
        ]);
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => $product->price,
        ]);

        $response = $this->actingAs($admin)->delete(route('products.destroy', $product));

        $response->assertRedirect(route('products.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('products', ['id' => $product->id]);
        $this->assertDatabaseHas('order_items', ['order_id' => $order->id, 'product_id' => $product->id]);
    }

    public function test_produk_tanpa_riwayat_pesanan_tetap_bisa_dihapus(): void
    {
        $admin = User::factory()->create();
        $admin->role = 'admin';
        $admin->save();

        $product = Product::factory()->create();

        $response = $this->actingAs($admin)->delete(route('products.destroy', $product));

        $response->assertRedirect(route('products.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}