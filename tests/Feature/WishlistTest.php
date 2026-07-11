<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WishlistTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_bisa_menambahkan_produk_ke_wishlist(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($user)
            ->postJson(route('wishlist.toggle', $product));

        $response->assertOk();
        $response->assertJson(['wishlisted' => true, 'wishlist_count' => 1]);

        $this->assertDatabaseHas('wishlists', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }

    public function test_toggle_wishlist_dua_kali_menghapus_produk_dari_wishlist(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $this->actingAs($user)->postJson(route('wishlist.toggle', $product));
        $response = $this->actingAs($user)->postJson(route('wishlist.toggle', $product));

        $response->assertOk();
        $response->assertJson(['wishlisted' => false, 'wishlist_count' => 0]);

        $this->assertDatabaseCount('wishlists', 0);
    }

    public function test_user_hanya_bisa_melihat_wishlist_miliknya_sendiri(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $product = Product::factory()->create();

        Wishlist::create(['user_id' => $userA->id, 'product_id' => $product->id]);

        $response = $this->actingAs($userB)->get(route('wishlist.index'));

        $response->assertOk();
        $response->assertViewHas('wishlists', function ($wishlists) {
            return $wishlists->isEmpty();
        });
    }

    public function test_guest_tidak_bisa_akses_wishlist(): void
    {
        $response = $this->get(route('wishlist.index'));

        $response->assertRedirect(route('login'));
    }
}