<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;


class ProductController extends Controller
{
    /**
     * PUBLIC — /katalog
     */
    public function index(Request $request)
    {
        $categories = Category::all();

        $products = Product::with('category')
            ->when($request->search, function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            })
            ->when($request->kategori, function ($query) use ($request) {
                $query->where('category_id', $request->kategori);
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('katalog', compact('products', 'categories'));
    }

    /**
     * PUBLIC — /katalog/{product}
     */
    public function show(Product $product)
    {
        $product->load(['category', 'images', 'variants', 'reviews.user']);

        $related = Product::where('id', '!=', $product->id)
            ->when($product->category_id, fn ($q) => $q->where('category_id', $product->category_id))
            ->latest()
            ->take(4)
            ->get();

        // Galeri: foto utama + foto tambahan, sudah jadi URL siap pakai
        $galleryImages = collect();
        if ($product->image) {
            $galleryImages->push(asset('storage/' . $product->image));
        }
        foreach ($product->images as $img) {
            $galleryImages->push(asset('storage/' . $img->image));
        }
        $galleryImages = $galleryImages->values();

        $averageRating = round($product->reviews->avg('rating') ?? 0, 1);
        $reviewsCount = $product->reviews->count();

        // Cek apakah user boleh kasih ulasan: sudah beli & pesanan selesai, belum pernah review
        $canReview = false;
        if (Auth::check()) {
            $hasPurchased = OrderItem::where('product_id', $product->id)
                ->whereHas('order', function ($query) {
                    $query->where('user_id', Auth::id())->where('status', 'Selesai');
                })
                ->exists();

            $alreadyReviewed = $product->reviews->contains('user_id', Auth::id());

            $canReview = $hasPurchased && ! $alreadyReviewed;
        }

        return view('product-detail', compact(
            'product', 'related', 'galleryImages', 'averageRating', 'reviewsCount', 'canReview'
        ));
    }

    /**
     * ADMIN ONLY — /products
     */
    public function manage(Request $request)
    {
        $products = Product::with(['category', 'variants'])->latest()->paginate(10)->withQueryString();

        $inStockCount = Product::where('stock', '>', 0)->count();
        $outOfStockCount = Product::where('stock', '<=', 0)->count();

        return view('admin.products.index', compact('products', 'inStockCount', 'outOfStockCount'));
    }

    /**
     * ADMIN ONLY — /products/create
     */
    public function create()
    {
        $categories = Category::all();

        return view('admin.products.create', compact('categories'));
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
            'images.*'    => 'nullable|image|max:2048',
            'variants.*.name'  => 'nullable|string|max:100',
            'variants.*.price' => 'nullable|numeric|min:0',
            'variants.*.stock' => 'nullable|integer|min:0',
            'roast_level' => 'nullable|string|max:100',
            'origin'      => 'nullable|string|max:255',
            'weight'      => 'nullable|string|max:50',
            'story'       => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product = Product::create([
            'name'        => $validated['name'],
            'category_id' => $validated['category_id'],
            'description' => $validated['description'] ?? null,
            'price'       => $validated['price'],
            'stock'       => $validated['stock'],
            'image'       => $validated['image'] ?? null,
            'is_featured' => $request->boolean('is_featured'),
            'roast_level' => $validated['roast_level'] ?? null,
            'origin'      => $validated['origin'] ?? null,
            'weight'      => $validated['weight'] ?? null,
            'story'       => $validated['story'] ?? null,
        ]);

        // Simpan foto tambahan
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image'      => $file->store('products', 'public'),
                    'sort_order' => $index,
                ]);
            }
        }

        // Simpan varian (baris yang namanya diisi saja)
        if ($request->has('variants')) {
            foreach ($request->variants as $index => $variant) {
                if (empty($variant['name'])) continue;

                $product->variants()->create([
                    'name'       => $variant['name'],
                    'price'      => $variant['price'] ?? $product->price,
                    'stock'      => $variant['stock'] ?? 0,
                    'sort_order' => $index,
                ]);
            }
        }

        ActivityLog::record('Produk', 'create', 'Menambahkan produk "' . $product->name . '".');

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * ADMIN ONLY — /products/{product}/edit
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        $product->load('images', 'variants');

        return view('admin.products.edit', compact('product', 'categories'));
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
            'images.*'    => 'nullable|image|max:2048',
            'variants.*.id'    => 'nullable|exists:product_variants,id',
            'variants.*.name'  => 'nullable|string|max:100',
            'variants.*.price' => 'nullable|numeric|min:0',
            'variants.*.stock' => 'nullable|integer|min:0',
            'roast_level' => 'nullable|string|max:100',
            'origin'      => 'nullable|string|max:255',
            'weight'      => 'nullable|string|max:50',
            'story'       => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            // Hapus file gambar lama dari storage supaya tidak numpuk jadi sampah
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $stockBefore = $product->stock;

        $product->update([
            'name'        => $validated['name'],
            'category_id' => $validated['category_id'],
            'description' => $validated['description'] ?? null,
            'price'       => $validated['price'],
            'stock'       => $validated['stock'],
            'image'       => $validated['image'] ?? $product->image,
            'is_featured' => $request->boolean('is_featured'),
            'roast_level' => $validated['roast_level'] ?? $product->roast_level,
            'origin'      => $validated['origin'] ?? $product->origin,
            'weight'      => $validated['weight'] ?? $product->weight,
            'story'       => $validated['story'] ?? $product->story,
        ]);

        // Tambah foto baru (foto lama tidak dihapus otomatis)
        if ($request->hasFile('images')) {
            $startOrder = $product->images()->max('sort_order') + 1;
            foreach ($request->file('images') as $index => $file) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image'      => $file->store('products', 'public'),
                    'sort_order' => $startOrder + $index,
                ]);
            }
        }

        // Update / tambah varian
        if ($request->has('variants')) {
            $submittedVariantIds = [];

            foreach ($request->variants as $index => $variant) {
                if (empty($variant['name'])) continue;

                $savedVariant = $product->variants()->updateOrCreate(
                    ['id' => $variant['id'] ?? null],
                    [
                        'name'       => $variant['name'],
                        'price'      => $variant['price'] ?? $product->price,
                        'stock'      => $variant['stock'] ?? 0,
                        'sort_order' => $index,
                    ]
                );

                $submittedVariantIds[] = $savedVariant->id;
            }

            // Varian lama yang sudah dibuang lewat tombol "Hapus" di form
            // (tidak lagi ada di request) ikut dihapus dari database —
            // sebelumnya cuma hilang dari tampilan tapi tetap ada di DB.
            $product->variants()->whereNotIn('id', $submittedVariantIds)->delete();
        }

        // Notifikasi stok menipis: hanya dikirim kalau stok BARU SAJA turun
        // melewati batas gara-gara admin ubah stok manual di sini.
        $lowStockThreshold = config('tepikopi.low_stock_threshold', 5);
        if ($validated['stock'] <= $lowStockThreshold && $stockBefore > $lowStockThreshold) {
            $admins = \App\Models\User::where('role', 'admin')->get();
            if ($admins->isNotEmpty()) {
                Notification::send(
                    $admins,
                    new \App\Notifications\LowStockNotification($product->name, $validated['stock'], $product->id)
                );
            }
        }

        ActivityLog::record('Produk', 'update', 'Memperbarui produk "' . $product->name . '".');

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * ADMIN ONLY — /products/{product}/images/{image}
     */
    public function destroyImage(Product $product, ProductImage $image)
    {
        if ($image->product_id !== $product->id) {
            abort(403);
        }

        Storage::disk('public')->delete($image->image);
        $image->delete();

        return back()->with('success', 'Foto berhasil dihapus.');
    }

    /**
     * ADMIN ONLY — DELETE /products/{product}
     */
   public function destroy(Product $product)
    {
        $productName = $product->name;

        // Hapus file gambar utama
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        // Hapus semua file foto tambahan
        foreach ($product->images as $img) {
            Storage::disk('public')->delete($img->image);
        }

        $product->delete();

        ActivityLog::record('Produk', 'delete', 'Menghapus produk "' . $productName . '".');

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }
}