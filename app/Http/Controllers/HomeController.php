<?php

namespace App\Http\Controllers;

use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::where('is_featured', true)->withAvg('reviews', 'rating')->latest()->take(4)->get();

        return view('homepage', compact('products'));
    }
}