<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Display a listing of all transactions.
     */
    public function index(Request $request)
    {
        $query = Transaction::with(['product', 'user', 'supplier', 'approver']);

        // Filter by date range
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        // Filter by type
        if ($request->type) {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Search by product name
        if ($request->search) {
            $query->whereHas('product', function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('code', 'like', "%{$request->search}%");
            });
        }

        $transactions = $query->latest()->paginate(15);
        
        return view('admin.transactions.index', compact('transactions'));
    }

    /**
     * Show form for stock in.
     */
    public function createStockIn()
    {
        $products = Product::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();
        
        return view('admin.transactions.stock-in', compact('products', 'suppliers'));
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
            'date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $product = Product::findOrFail($request->product_id);

            // Create transaction
            $transaction = Transaction::create([
                'product_id' => $request->product_id,
                'user_id' => Auth::id(),
                'supplier_id' => $request->supplier_id,
                'type' => 'masuk',
                'quantity' => $request->quantity,
                'date' => $request->date,
                'notes' => $request->notes,
                'status' => 'pending', // Menunggu approval KTU
            ]);

            // Update stock temporarily
            $product->stock += $request->quantity;
            $product->save();

            DB::commit();

            return redirect()->route('admin.transactions.index')
                ->with('success', 'Stok masuk berhasil dicatat dan menunggu approval KTU.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mencatat stok masuk: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show form for stock out.
     */
    public function createStockOut()
    {
        $products = Product::orderBy('name')->get();
        
        return view('admin.transactions.stock-out', compact('products'));
    }

    /**
     * Store stock out transaction.
     */
    public function storeStockOut(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $product = Product::findOrFail($request->product_id);

            // Check stock
            if ($product->stock < $request->quantity) {
                return back()->with('error', 'Stok tidak mencukupi. Stok tersedia: ' . $product->stock . ' ' . $product->unit)
                    ->withInput();
            }

            // Create transaction
            $transaction = Transaction::create([
                'product_id' => $request->product_id,
                'user_id' => Auth::id(),
                'type' => 'keluar',
                'quantity' => $request->quantity,
                'date' => $request->date,
                'notes' => $request->notes,
                'status' => 'pending',
            ]);

            // Update stock
            $product->stock -= $request->quantity;
            $product->save();

            DB::commit();

            return redirect()->route('admin.transactions.index')
                ->with('success', 'Stok keluar berhasil dicatat dan menunggu approval KTU.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mencatat stok keluar: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display pending transactions for approval.
     */
    public function pending()
    {
        $transactions = Transaction::with(['product', 'user'])
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

            $transaction = Transaction::with('product')->findOrFail($id);

            if ($transaction->status != 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaksi sudah diproses sebelumnya.'
                ], 400);
            }

            // Update status
            $transaction->update([
                'status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil disetujui.'
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

            $transaction = Transaction::with('product')->findOrFail($id);

            if ($transaction->status != 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaksi sudah diproses sebelumnya.'
                ], 400);
            }

            // Rollback stock
            $product = $transaction->product;
            if ($transaction->type == 'masuk') {
                $product->stock -= $transaction->quantity;
            } else {
                $product->stock += $transaction->quantity;
            }
            $product->save();

            // Update status
            $transaction->update([
                'status' => 'rejected',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi ditolak dan stok dikembalikan.'
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
     * Show transaction detail.
     */
    public function show($id)
    {
        $transaction = Transaction::with(['product', 'user', 'supplier', 'approver'])
            ->findOrFail($id);

        return view('admin.transactions.show', compact('transaction'));
    }

    /**
     * Get transaction statistics for dashboard.
     */
    public function getStats()
    {
        $stats = [
            'total_masuk' => Transaction::where('type', 'masuk')
                ->where('status', 'approved')
                ->sum('quantity'),
            'total_keluar' => Transaction::where('type', 'keluar')
                ->where('status', 'approved')
                ->sum('quantity'),
            'pending_count' => Transaction::where('status', 'pending')->count(),
            'today_masuk' => Transaction::where('type', 'masuk')
                ->whereDate('created_at', today())
                ->sum('quantity'),
            'today_keluar' => Transaction::where('type', 'keluar')
                ->whereDate('created_at', today())
                ->sum('quantity'),
        ];

        return response()->json($stats);
    }
}   