<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * POST /katalog/{product}/reviews
     */
    /**
 * ADMIN ONLY — GET /reviews
 */
public function index(Request $request)
{
    $query = Review::with(['product', 'user'])->latest();

    if ($request->filled('rating')) {
        $query->where('rating', $request->rating);
    }

    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('comment', 'like', "%{$search}%")
                ->orWhereHas('product', fn ($p) => $p->where('name', 'like', "%{$search}%"))
                ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%"));
        });
    }

    $reviews = $query->paginate(10)->withQueryString();
    $totalReviews  = Review::count();
    $averageRating = round(Review::avg('rating') ?? 0, 1);

    return view('admin.reviews.index', compact('reviews', 'totalReviews', 'averageRating'));
}

/**
 * ADMIN ONLY — DELETE /reviews/{review}
 */
public function destroy(Review $review)
{
    $productName = $review->product->name ?? 'produk';
    $reviewerName = $review->user->name ?? 'user';
    $review->delete();

    \App\Models\ActivityLog::record('Ulasan', 'delete', 'Menghapus ulasan dari "' . $reviewerName . '" untuk produk "' . $productName . '".');

    return back()->with('success', 'Ulasan berhasil dihapus.');
}
    public function store(Request $request, Product $product)
    {
        $userId = Auth::id();

        // Cek sudah pernah beli produk ini dan pesanannya berstatus Selesai
        $hasPurchased = OrderItem::where('product_id', $product->id)
            ->whereHas('order', function ($query) use ($userId) {
                $query->where('user_id', $userId)->where('status', 'Selesai');
            })
            ->exists();

        if (! $hasPurchased) {
            return back()->with('error', 'Kamu hanya bisa memberi ulasan untuk produk yang sudah kamu beli dan pesanannya selesai.');
        }

        // Cek belum pernah review produk ini
        $alreadyReviewed = Review::where('product_id', $product->id)
            ->where('user_id', $userId)
            ->exists();

        if ($alreadyReviewed) {
            return back()->with('error', 'Kamu sudah pernah memberi ulasan untuk produk ini.');
        }

        $validated = $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        Review::create([
            'product_id' => $product->id,
            'user_id'    => $userId,
            'rating'     => $validated['rating'],
            'comment'    => $validated['comment'] ?? null,
        ]);

        return back()->with('success', 'Terima kasih atas ulasannya!');
    }

    /**
     * CUSTOMER — PUT /my-reviews/{review}
     * Customer mengedit ulasannya sendiri (rating & komentar).
     */
    public function updateOwn(Request $request, Review $review)
    {
        abort_unless($review->user_id === Auth::id(), 403);

        $validated = $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $review->update($validated);

        return back()->with('success', 'Ulasan berhasil diperbarui.');
    }

    /**
     * CUSTOMER — DELETE /my-reviews/{review}
     * Customer menghapus ulasannya sendiri.
     */
    public function destroyOwn(Review $review)
    {
        abort_unless($review->user_id === Auth::id(), 403);

        $review->delete();

        return back()->with('success', 'Ulasan berhasil dihapus.');
    }
}