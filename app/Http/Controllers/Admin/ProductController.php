<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->paginate(10);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:products',
            'name' => 'required',
            'category_id' => 'nullable|exists:categories,id',
            'min_stock' => 'numeric|min:0',
            'unit' => 'required',
            'price' => 'numeric|min:0'
        ]);

        Product::create($request->all());
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'code' => 'required|unique:products,code,' . $product->id,
            'name' => 'required',
            'category_id' => 'nullable|exists:categories,id',
            'min_stock' => 'numeric|min:0',
            'unit' => 'required',
            'price' => 'numeric|min:0'
        ]);

        $product->update($request->all());
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diupdate');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['success' => true]);
    }

    public function updateStock(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->stock += $request->quantity;
        $product->save();

        return response()->json([
            'success' => true,
            'new_stock' => $product->stock
        ]);
    }
}   