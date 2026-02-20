<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::paginate(10);
        return view('suppliers.index', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'nullable',
            'address' => 'nullable'
        ]);

        Supplier::create($request->all());
        return redirect()->back()->with('success', 'Supplier berhasil ditambahkan');
    }

    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'nullable',
            'address' => 'nullable'
        ]);

        $supplier->update($request->all());
        return redirect()->back()->with('success', 'Supplier berhasil diupdate');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return response()->json(['success' => true]);
    }
}