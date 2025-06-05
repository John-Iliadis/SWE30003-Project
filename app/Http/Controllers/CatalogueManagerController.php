<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CatalogueManagerController extends Controller
{
    public function index()
    {
        $products = Product::latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all(); // Get all categories for the form
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'brand' => 'required|max:255',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_name' => 'required|exists:categories,category_name',
            'image_url' => 'nullable|url'
        ]);

        Product::create($validated);
        return redirect()->route('admin.products.index')->with('success', 'Product created');
    }

    public function edit(Product $product)
    {
        $categories = Category::all(); // Get all categories for the form
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'brand' => 'required|max:255',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_name' => 'required|exists:categories,category_name',
            'image_url' => 'nullable|url'
        ]);
    
        $product->update($validated);
        return redirect()->route('admin.products.index')->with('success', 'Product updated');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted');
    }
}
