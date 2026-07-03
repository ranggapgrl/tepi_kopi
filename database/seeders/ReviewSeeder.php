<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan ada beberapa akun pelanggan dummy untuk penulis ulasan
        $customers = collect([
            ['name' => 'Budi Santoso', 'email' => 'customer@demo.com'],
            ['name' => 'Siti Rahma', 'email' => 'siti.rahma@demo.com'],
            ['name' => 'Andi Wijaya', 'email' => 'andi.wijaya@demo.com'],
            ['name' => 'Dewi Lestari', 'email' => 'dewi.lestari@demo.com'],
            ['name' => 'Fajar Nugroho', 'email' => 'fajar.nugroho@demo.com'],
        ])->map(function ($data) {
            return User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => bcrypt('password'),
                    // 'role' sengaja tidak diisi di sini — kolom 'role' punya
                    // default 'customer' di database, dan sudah tidak lagi
                    // mass-assignable demi keamanan (lihat App\Models\User).
                ]
            );
        });

        // Pastikan ada beberapa produk dummy sebagai target ulasan
        $biji = Category::firstOrCreate(['name' => 'Biji Kopi']);
        $alat = Category::firstOrCreate(['name' => 'Alat Kopi']);

        $products = collect([
            [
                'category_id' => $biji->id,
                'name' => 'Arabika Gayo',
                'description' => 'Kopi single origin dari dataran tinggi Gayo, Aceh.',
                'price' => 85000,
                'stock' => 50,
            ],
            [
                'category_id' => $alat->id,
                'name' => 'V60 Dripper',
                'description' => 'Alat seduh kopi manual metode pour over.',
                'price' => 150000,
                'stock' => 20,
            ],
            [
                'category_id' => $biji->id,
                'name' => 'Robusta Lampung',
                'description' => 'Kopi robusta pekat dan bold dari Lampung.',
                'price' => 65000,
                'stock' => 40,
            ],
        ])->map(function ($data) {
            return Product::firstOrCreate(['name' => $data['name']], $data);
        });

        // Dummy ulasan
        $dummyReviews = [
            ['product' => 'Arabika Gayo', 'user' => 'siti.rahma@demo.com', 'rating' => 5, 'comment' => 'Aromanya kuat banget dan rasanya smooth, cocok buat pecinta kopi hitam. Bakal repeat order!'],
            ['product' => 'Arabika Gayo', 'user' => 'andi.wijaya@demo.com', 'rating' => 4, 'comment' => 'Enak, tapi menurut saya agak asam kalau diseduh terlalu panas. Overall puas.'],
            ['product' => 'Arabika Gayo', 'user' => 'dewi.lestari@demo.com', 'rating' => 5, 'comment' => 'Packaging rapi, biji kopinya fresh, pengiriman juga cepat. Recommended!'],
            ['product' => 'V60 Dripper', 'user' => 'fajar.nugroho@demo.com', 'rating' => 4, 'comment' => 'Kualitas bagus untuk harga segini, cuma agak riskan pecah kalau tidak hati-hati.'],
            ['product' => 'V60 Dripper', 'user' => 'customer@demo.com', 'rating' => 5, 'comment' => 'Alat seduhnya presisi, hasil kopi jadi lebih konsisten dari biasanya.'],
            ['product' => 'Robusta Lampung', 'user' => 'siti.rahma@demo.com', 'rating' => 3, 'comment' => 'Rasanya cukup pahit, mungkin cocok buat yang suka kopi strong. Kurang cocok di lidah saya.'],
            ['product' => 'Robusta Lampung', 'user' => 'andi.wijaya@demo.com', 'rating' => 4, 'comment' => null],
            ['product' => 'Robusta Lampung', 'user' => 'dewi.lestari@demo.com', 'rating' => 2, 'comment' => 'Kopi datang agak lama dan kemasan sedikit rusak, semoga bisa diperbaiki ke depannya.'],
        ];

        foreach ($dummyReviews as $data) {
            $product = $products->firstWhere('name', $data['product']);
            $user = $customers->firstWhere('email', $data['user']);

            Review::firstOrCreate(
                ['product_id' => $product->id, 'user_id' => $user->id],
                ['rating' => $data['rating'], 'comment' => $data['comment']]
            );
        }
    }
}