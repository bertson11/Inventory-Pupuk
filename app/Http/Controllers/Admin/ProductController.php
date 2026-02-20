<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Search
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('code', 'like', "%{$request->search}%");
            });
        }

        // Filter by category
        if ($request->category) {
            $query->where('category_id', $request->category);
        }

        // Filter by stock status
        if ($request->status) {
            if ($request->status == 'menipis') {
                $query->whereColumn('stock', '<=', 'min_stock')
                      ->where('stock', '>', 0);
            } elseif ($request->status == 'kritis') {
                $query->where('stock', '<=', 0);
            } elseif ($request->status == 'aman') {
                $query->whereColumn('stock', '>', 'min_stock');
            }
        }

        $products = $query->orderBy('name')->paginate(15);
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Show form for creating new product.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created product.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:products',
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'min_stock' => 'nullable|integer|min:0',
            'unit' => 'required|string|max:20',
            'price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            Product::create([
                'code' => $request->code,
                'name' => $request->name,
                'category_id' => $request->category_id,
                'min_stock' => $request->min_stock ?? 5,
                'unit' => $request->unit,
                'price' => $request->price ?? 0,
                'description' => $request->description,
                'stock' => 0, // Stok awal 0
            ]);

            DB::commit();

            return redirect()->route('admin.products.index')
                ->with('success', 'Produk berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menambahkan produk: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show form for editing product.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'code' => 'required|unique:products,code,' . $product->id,
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'min_stock' => 'nullable|integer|min:0',
            'unit' => 'required|string|max:20',
            'price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $product->update([
                'code' => $request->code,
                'name' => $request->name,
                'category_id' => $request->category_id,
                'min_stock' => $request->min_stock ?? 5,
                'unit' => $request->unit,
                'price' => $request->price ?? 0,
                'description' => $request->description,
            ]);

            DB::commit();

            return redirect()->route('admin.products.index')
                ->with('success', 'Produk berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui produk: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified product.
     */
    public function destroy(Product $product)
    {
        try {
            DB::beginTransaction();

            // Cek apakah ada transaksi terkait
            if ($product->transactions()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produk tidak dapat dihapus karena sudah memiliki riwayat transaksi.'
                ], 400);
            }

            $product->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil dihapus.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus produk: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update stock manually (for admin).
     */
    public function updateStock(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer',
            'type' => 'required|in:add,subtract'
        ]);

        try {
            DB::beginTransaction();

            $product = Product::findOrFail($id);

            if ($request->type == 'add') {
                $product->stock += $request->quantity;
            } else {
                if ($product->stock < $request->quantity) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Stok tidak mencukupi. Stok saat ini: ' . $product->stock
                    ], 400);
                }
                $product->stock -= $request->quantity;
            }

            $product->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Stok berhasil diperbarui',
                'new_stock' => $product->stock
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui stok: ' . $e->getMessage()
            ], 500);
        }
    }
}