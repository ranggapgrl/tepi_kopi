<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * PUBLIC — /katalog
     * Halaman belanja untuk semua pengunjung, tanpa kontrol admin.
     */
    public function index()
    {
        $products = Product::with('category')->latest()->get();

        return view('katalog', compact('products'));
    }

    /**
     * PUBLIC — /katalog/{product}
     * Halaman detail satu produk.
     */
    public function show(Product $product)
    {
        $related = Product::where('id', '!=', $product->id)
            ->when($product->category_id, fn ($q) => $q->where('category_id', $product->category_id))
            ->latest()
            ->take(4)
            ->get();

        return view('product-detail', compact('product', 'related'));
    }

    /**
     * ADMIN ONLY — /products
     * Tabel kelola produk (tambah/edit/hapus). Route ini dilindungi
     * middleware ['auth', 'admin'] di routes/web.php.
     */
    public function manage()
    {
        $products = Product::with('category')->latest()->get();

        return view('products.index', compact('products'));
    }

    /**
     * ADMIN ONLY — /products/create
     */
    public function create()
    {
        $categories = Category::all();

        return view('products.create', compact('categories'));
    }

    /**
     * ADMIN ONLY — POST /products
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'image'       => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * ADMIN ONLY — /products/{product}/edit
     */
    public function edit(Product $product)
    {
        $categories = Category::all();

        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * ADMIN ONLY — PUT /products/{product}
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'image'       => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * ADMIN ONLY — DELETE /products/{product}
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }
}