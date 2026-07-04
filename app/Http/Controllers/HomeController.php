<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::where('is_featured', true)->latest()->take(4)->get();
        return view('homepage', compact('products'));
    }
}