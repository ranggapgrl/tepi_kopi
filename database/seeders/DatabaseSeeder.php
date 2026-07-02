<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Menambahkan daftar kategori menu untuk Tepi Kopi
        Category::create(['name' => 'Signature Coffee']);
        Category::create(['name' => 'Espresso Based']);
        Category::create(['name' => 'Manual Brew']);
        Category::create(['name' => 'Non-Coffee']);
        Category::create(['name' => 'Pastry & Snacks']);
    }
}