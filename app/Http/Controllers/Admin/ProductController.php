<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product; // Make sure this points to your Product model
use App\Models\Category; // We'll need this for product forms
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Add this import for the Log facade

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Change this line in the index method:
        $products = Product::latest()->simplePaginate(10); // This only shows Previous/Next
        return view('admin.products.index', compact('products')); // We'll create this view
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all(); // Get all categories for the form
        return view('admin.products.create', compact('categories')); // We'll create this view
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation based on your Product model fields
        $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_name' => 'required|exists:categories,category_name',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $data = $request->except('image');
        
        // Handle image upload with detailed error logging
        if ($request->hasFile('image')) {
            try {
                $image = $request->file('image');
                
                // Log file information
                Log::info('Uploading image: ' . $image->getClientOriginalName());
                Log::info('Image is valid: ' . ($image->isValid() ? 'Yes' : 'No'));
                
                if ($image->isValid()) {
                    $imageName = time() . '.' . $image->getClientOriginalExtension();
                    $targetPath = public_path('img/products');
                    
                    // Log directory information
                    Log::info('Target directory: ' . $targetPath);
                    Log::info('Directory exists: ' . (file_exists($targetPath) ? 'Yes' : 'No'));
                    Log::info('Directory is writable: ' . (is_writable($targetPath) ? 'Yes' : 'No'));
                    
                    $image->move($targetPath, $imageName);
                    $data['image_url'] = 'img/products/' . $imageName;
                    
                    Log::info('Image uploaded successfully to: ' . $data['image_url']);
                }
            } catch (\Exception $e) {
                Log::error('Image upload failed: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Image upload failed: ' . $e->getMessage());
            }
        } else {
            Log::info('No image file provided in the request');
        }
        
        Product::create($data);
    
        return redirect()->route('admin.products.index')
                         ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        // Typically, for an admin panel, you might redirect to edit or just show details
        return view('admin.products.show', compact('product')); // We'll create this view
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories')); // We'll create this view
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_name' => 'required|exists:categories,category_name',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        $data = $request->except('image');
        
        // Handle image upload with detailed error logging
        if ($request->hasFile('image')) {
            try {
                $image = $request->file('image');
                
                // Log file information
                Log::info('Updating image: ' . $image->getClientOriginalName());
                Log::info('Image is valid: ' . ($image->isValid() ? 'Yes' : 'No'));
                
                if ($image->isValid()) {
                    // Delete old image if it exists
                    if ($product->image_url && file_exists(public_path($product->image_url))) {
                        Log::info('Deleting old image: ' . $product->image_url);
                        unlink(public_path($product->image_url));
                    }
                    
                    $imageName = time() . '.' . $image->getClientOriginalExtension();
                    $targetPath = public_path('img/products');
                    
                    // Log directory information
                    Log::info('Target directory: ' . $targetPath);
                    Log::info('Directory exists: ' . (file_exists($targetPath) ? 'Yes' : 'No'));
                    Log::info('Directory is writable: ' . (is_writable($targetPath) ? 'Yes' : 'No'));
                    
                    $image->move($targetPath, $imageName);
                    $data['image_url'] = 'img/products/' . $imageName;
                    
                    Log::info('Image updated successfully to: ' . $data['image_url']);
                }
            } catch (\Exception $e) {
                Log::error('Image update failed: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Image update failed: ' . $e->getMessage());
            }
        } else {
            Log::info('No new image file provided in the update request');
        }
        
        $product->update($data);
    
        return redirect()->route('admin.products.index')
                         ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')
                         ->with('success', 'Product deleted successfully.');
    }
}