<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    // Halaman untuk PELANGGAN (Katalog)
    public function index()
    {
        // Menampilkan semua produk untuk user
        $products = Product::with('category')->latest()->get();
        return view('products.index', compact('products'));
    }

    // --- FITUR KHUSUS ADMIN (Dilindungi Middleware 'admin') ---

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $imagePath = null;
        if($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        Product::create([
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'description' => $request->description,
            'price'       => $request->price,
            'stock'       => $request->stock,
            'image'       => $imagePath
        ]);

        return redirect('/products')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function destroy(Product $product)
    {
        // Hapus file gambar jika ada
        if($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect('/products')->with('success', 'Produk berhasil dihapus.');
    }
}