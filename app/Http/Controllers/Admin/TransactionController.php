<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Display a listing of all transactions.
     */
    public function index()
    {
        $transactions = Transaction::with('product', 'user', 'supplier')
                            ->latest()
                            ->paginate(15);
        return view('admin.transactions.index', compact('transactions'));
    }

    /**
     * Display pending transactions.
     */
    public function pending()
    {
        $transactions = Transaction::with('product', 'user')
                            ->where('status', 'pending')
                            ->latest()
                            ->get();
        return view('admin.transactions.pending', compact('transactions'));
    }

    /**
     * Approve a transaction.
     */
    public function approve($id)
    {
        try {
            DB::beginTransaction();
            
            $transaction = Transaction::findOrFail($id);
            
            // Update status
            $transaction->update([
                'status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now()
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil disetujui'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyetujui transaksi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject a transaction.
     */
    public function reject($id)
    {
        try {
            DB::beginTransaction();
            
            $transaction = Transaction::findOrFail($id);
            
            // Rollback stok jika perlu
            if ($transaction->status == 'pending') {
                $product = Product::find($transaction->product_id);
                
                if ($transaction->type == 'masuk') {
                    $product->stock -= $transaction->quantity;
                } else {
                    $product->stock += $transaction->quantity;
                }
                $product->save();
            }
            
            // Update status
            $transaction->update([
                'status' => 'rejected',
                'approved_by' => Auth::id(),
                'approved_at' => now()
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Transaksi ditolak'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menolak transaksi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show form for stock in.
     */
    public function createStockIn()
    {
        $products = Product::all();
        $suppliers = \App\Models\Supplier::all();
        return view('krani.stock-in', compact('products', 'suppliers'));
    }

    /**
     * Store stock in transaction.
     */
    public function storeStockIn(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'notes' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            $transaction = Transaction::create([
                'product_id' => $request->product_id,
                'user_id' => Auth::id(),
                'supplier_id' => $request->supplier_id,
                'type' => 'masuk',
                'quantity' => $request->quantity,
                'notes' => $request->notes,
                'status' => 'pending'
            ]);

            // Update stok sementara
            $product = Product::find($request->product_id);
            $product->stock += $request->quantity;
            $product->save();

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true, 
                    'message' => 'Stok masuk dicatat, menunggu approval'
                ]);
            }

            return redirect()->back()->with('success', 'Stok masuk berhasil dicatat');

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false, 
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show form for stock out.
     */
    public function createStockOut()
    {
        $products = Product::all();
        return view('krani.stock-out', compact('products'));
    }

    /**
     * Store stock out transaction.
     */
    public function storeStockOut(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            $product = Product::find($request->product_id);
            
            if ($product->stock < $request->quantity) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Stok tidak mencukupi. Tersedia: ' . $product->stock
                ], 400);
            }

            $transaction = Transaction::create([
                'product_id' => $request->product_id,
                'user_id' => Auth::id(),
                'type' => 'keluar',
                'quantity' => $request->quantity,
                'notes' => $request->notes,
                'status' => 'pending'
            ]);

            $product->stock -= $request->quantity;
            $product->save();

            DB::commit();

            return response()->json([
                'success' => true, 
                'message' => 'Stok keluar dicatat, menunggu approval'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false, 
                'message' => $e->getMessage()
            ], 500);
        }
    }
}