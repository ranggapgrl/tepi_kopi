<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Coupon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@demo.com'],
            [
                'name' => 'Admin Tepi Kopi',
                'password' => bcrypt('password'),
            ]
        );
        $admin->role = 'admin';
        $admin->save();

        // Customer
        $customer = User::firstOrCreate(
            ['email' => 'customer@demo.com'],
            [
                'name' => 'Budi Santoso',
                'password' => bcrypt('password'),
            ]
        );
        $customer->role = 'customer';
        $customer->save();

        // Kategori
        $biji = Category::firstOrCreate(['name' => 'Biji Kopi']);
        $alat = Category::firstOrCreate(['name' => 'Alat Kopi']);
        $aksesoris = Category::firstOrCreate(['name' => 'Aksesoris']);

        // Produk
        Product::firstOrCreate(
            ['name' => 'Arabika Gayo'],
            [
                'category_id' => $biji->id,
                'description' => 'Kopi single origin dari dataran tinggi Gayo, Aceh.',
                'price' => 85000,
                'stock' => 50,
            ]
        );

        Product::firstOrCreate(
            ['name' => 'V60 Dripper'],
            [
                'category_id' => $alat->id,
                'description' => 'Alat seduh kopi manual metode pour over.',
                'price' => 150000,
                'stock' => 20,
            ]
        );

        // Kupon contoh
        Coupon::firstOrCreate(
            ['code' => 'TEPIKOPI10'],
            [
                'type' => 'percentage',
                'value' => 10,
                'min_purchase' => 50000,
                'max_discount' => 20000,
                'usage_limit' => null,
                'is_active' => true,
            ]
        );

        Coupon::firstOrCreate(
            ['code' => 'HEMAT15K'],
            [
                'type' => 'fixed',
                'value' => 15000,
                'min_purchase' => 100000,
                'usage_limit' => 100,
                'is_active' => true,
            ]
        );

        $this->call(ReviewSeeder::class);
    }
}