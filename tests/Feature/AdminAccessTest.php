<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_tidak_bisa_akses_halaman_admin(): void
    {
        $customer = User::factory()->create();
        $customer->role = 'customer';
        $customer->save();

        $response = $this->actingAs($customer)->get('/admin');

        $response->assertForbidden();
    }

    public function test_admin_bisa_akses_halaman_admin(): void
    {
        $admin = User::factory()->create();
        $admin->role = 'admin';
        $admin->save();

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertOk();
    }

    public function test_guest_diarahkan_ke_login_saat_akses_admin(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect(route('login'));
    }
}