<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->paginate(10);

        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        // Validation rules
                $validated = $request->validate([
                'name'           => 'required|string|max:255',
                'category'       => 'required|string|max:100',
                'unit'           => 'required|string|max:50',
                'price'          => 'required|numeric|min:0',
                'stock_quantity' => 'required|integer|min:0',
                'cost_price'     => 'required|numeric|min:0',
                'selling_price'  => 'required|numeric|min:0',  
                'image'          => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            ]);
        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        // Validation rules
        $validated = $request->validate([
                'name'           => 'required|string|max:255',
                'category'       => 'required|string|max:100',
                'unit'           => 'required|string|max:50',
                'price'          => 'required|numeric|min:0',
                'stock_quantity' => 'required|integer|min:0',
                'cost_price'     => 'required|numeric|min:0',
                'selling_price'  => 'required|numeric|min:0',  
                'image'          => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
{
    try {
        if ($product->image) {
            // Check if file exists before deleting
            if (Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            } else {
                \Log::warning("Product image not found for deletion: " . $product->image);
            }
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    } catch (\Exception $e) {
        \Log::error("Error deleting product: " . $e->getMessage());
        return redirect()->route('products.index')->with('error', 'Failed to delete product.');
    }
}

}
