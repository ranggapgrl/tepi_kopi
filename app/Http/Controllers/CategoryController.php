<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * ADMIN ONLY — /categories
     */
    public function index()
    {
        $categories = Category::withCount('products')->latest()->get();

        return view('categories.index', compact('categories'));
    }

    /**
     * ADMIN ONLY — /categories/create
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * ADMIN ONLY — POST /categories
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        Category::create($validated);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * ADMIN ONLY — /categories/{category}/edit
     */
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * ADMIN ONLY — PUT /categories/{category}
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        ]);

        $category->update($validated);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * ADMIN ONLY — DELETE /categories/{category}
     */
    public function destroy(Category $category)
    {
        if ($category->products()->exists()) {
            return redirect()->route('categories.index')
                ->with('error', 'Kategori "' . $category->name . '" masih dipakai oleh produk, tidak bisa dihapus.');
        }

        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}