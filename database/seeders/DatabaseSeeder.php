<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        $admin = User::create([
            'name' => 'Admin Tepi Kopi',
            'email' => 'admin@demo.com',
            'password' => bcrypt('password'),
        ]);
        $admin->role = 'admin';
        $admin->save();

        // Customer
        $customer = User::create([
            'name' => 'Budi Santoso',
            'email' => 'customer@demo.com',
            'password' => bcrypt('password'),
        ]);
        $customer->role = 'customer';
        $customer->save();

        // Kategori
        $biji = Category::create(['name' => 'Biji Kopi']);
        $alat = Category::create(['name' => 'Alat Kopi']);
        $aksesoris = Category::create(['name' => 'Aksesoris']);

        // Produk
        Product::create([
            'category_id' => $biji->id,
            'name' => 'Arabika Gayo',
            'description' => 'Kopi single origin dari dataran tinggi Gayo, Aceh.',
            'price' => 85000,
            'stock' => 50,
        ]);

        Product::create([
            'category_id' => $alat->id,
            'name' => 'V60 Dripper',
            'description' => 'Alat seduh kopi manual metode pour over.',
            'price' => 150000,
            'stock' => 20,
        ]);

        $this->call(ReviewSeeder::class);
    }
}