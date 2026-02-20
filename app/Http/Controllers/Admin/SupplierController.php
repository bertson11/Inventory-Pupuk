<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    /**
     * Display a listing of suppliers.
     */
    public function index(Request $request)
    {
        $query = Supplier::query();

        // Search
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('phone', 'like', "%{$request->search}%")
                  ->orWhere('address', 'like', "%{$request->search}%");
            });
        }

        $suppliers = $query->orderBy('name')->paginate(10);
        
        return view('admin.suppliers.index', compact('suppliers'));
    }

    /**
     * Show form for creating new supplier.
     */
    public function create()
    {
        return view('admin.suppliers.create');
    }

    /**
     * Store a newly created supplier.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:200',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            Supplier::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'address' => $request->address,
                'description' => $request->description,
            ]);

            DB::commit();

            return redirect()->route('admin.suppliers.index')
                ->with('success', 'Supplier berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menambahkan supplier: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show form for editing supplier.
     */
    public function edit(Supplier $supplier)
    {
        return view('admin.suppliers.edit', compact('supplier'));
    }

    /**
     * Update the specified supplier.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'name' => 'required|string|max:200',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $supplier->update([
                'name' => $request->name,
                'phone' => $request->phone,
                'address' => $request->address,
                'description' => $request->description,
            ]);

            DB::commit();

            return redirect()->route('admin.suppliers.index')
                ->with('success', 'Supplier berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui supplier: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified supplier.
     */
    public function destroy(Supplier $supplier)
    {
        try {
            DB::beginTransaction();

            // Cek apakah supplier masih digunakan oleh transaksi
            if ($supplier->transactions()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Supplier tidak dapat dihapus karena masih memiliki ' . $supplier->transactions()->count() . ' transaksi.'
                ], 400);
            }

            $supplier->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Supplier berhasil dihapus.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus supplier: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get suppliers for API (AJAX request).
     */
    public function getSuppliers(Request $request)
    {
        $search = $request->search;
        $suppliers = Supplier::when($search, function($query, $search) {
                return $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->limit(10)
            ->get(['id', 'name', 'phone']);

        return response()->json($suppliers);
    }
}