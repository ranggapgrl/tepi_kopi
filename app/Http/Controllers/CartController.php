<?php
namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        // Mencari keranjang milik user yang sedang login, jika tidak ada, buat baru
        $cart = Cart::firstOrCreate(['user_id' => Auth::id() ?? 1]); // Default 1 jika blm ada sistem login
        
        // Mengambil isi keranjang beserta relasi produknya
        $cartItems = CartItem::with('product')->where('cart_id', $cart->id)->get();
        
        // Hitung total harga
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item->product->price * $item->quantity;
        }
        
        $tax = $subtotal * 0.11; // Pajak 11%
        $total = $subtotal + $tax;

        return view('cart.index', compact('cartItems', 'subtotal', 'tax', 'total'));
    }

    public function store(Request $request)
    {
        $cart = Cart::firstOrCreate(['user_id' => Auth::id() ?? 1]);
        
        // Cek apakah barang sudah ada di keranjang
        $existingItem = CartItem::where('cart_id', $cart->id)
                                ->where('product_id', $request->product_id)
                                ->first();

        if ($existingItem) {
            $existingItem->increment('quantity', $request->quantity ?? 1);
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity ?? 1
            ]);
        }

        return redirect('/cart')->with('success', 'Kopi berhasil ditambahkan ke keranjang!');
    }
}