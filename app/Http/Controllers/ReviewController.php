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
}