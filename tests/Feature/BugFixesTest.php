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

    public function test_hapus_produk_langsung_dari_database_tidak_merusak_riwayat_pesanan(): void
    {
        // Simulasikan penghapusan produk yang melewati guard controller (mis.
        // lewat tinker / query manual langsung), untuk memastikan FK
        // product_id sudah nullOnDelete (bukan lagi cascadeOnDelete) dan
        // snapshot product_name tetap menyimpan nama aslinya.
        $user = User::factory()->create();
        $product = Product::factory()->create(['name' => 'Kopi Gayo Arabica']);

        $order = Order::create([
            'user_id' => $user->id,
            'total_price' => $product->price,
            'status' => 'Selesai',
        ]);
        $orderItem = OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'quantity' => 1,
            'price' => $product->price,
        ]);

        $product->delete();

        $orderItem->refresh();

        $this->assertDatabaseHas('order_items', ['id' => $orderItem->id]);
        $this->assertNull($orderItem->product_id);
        $this->assertSame('Kopi Gayo Arabica', $orderItem->product_name);
        $this->assertSame('Kopi Gayo Arabica', $orderItem->display_name);
    }
}