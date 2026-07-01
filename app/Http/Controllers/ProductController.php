<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->get();

        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();

        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $imagePath = null;

        if($request->hasFile('image'))
        {
            $imagePath = $request->file('image')
                ->store('products','public');
        }

        Product::create([
            'category_id'=>$request->category_id,
            'name'=>$request->name,
            'description'=>$request->description,
            'price'=>$request->price,
            'stock'=>$request->stock,
            'image'=>$imagePath
        ]);

        return redirect('/products');
    }

    public function destroy(Product $product)
    {
        if($product->image)
        {
            Storage::disk('public')
                ->delete($product->image);
        }

        $product->delete();

        return redirect('/products');
    }
}