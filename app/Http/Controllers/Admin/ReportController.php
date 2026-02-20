<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * Display report dashboard.
     */
    public function index()
    {
        $data = [
            'totalProducts' => Product::count(),
            'totalTransactions' => Transaction::count(),
            'totalCategories' => Category::count(),
            'totalSuppliers' => Supplier::count(),
            'stockValue' => Product::sum(DB::raw('stock * price')),
            'monthlyTransactions' => Transaction::select(
                    DB::raw('EXTRACT(MONTH FROM created_at) as month'),
                    DB::raw('EXTRACT(YEAR FROM created_at) as year'),
                    DB::raw('COUNT(*) as total'),
                    DB::raw("SUM(CASE WHEN type = 'masuk' THEN quantity ELSE 0 END) as total_masuk"),
                    DB::raw("SUM(CASE WHEN type = 'keluar' THEN quantity ELSE 0 END) as total_keluar")
                )
                ->whereYear('created_at', date('Y'))
                ->groupBy('year', 'month')
                ->orderBy('year')
                ->orderBy('month')
                ->get(),
        ];

        return view('admin.reports.index', compact('data'));
    }

    /**
     * Stock report.
     */
    public function stock(Request $request)
    {
        $query = Product::with('category');

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

        // Search
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('code', 'like', "%{$request->search}%");
            });
        }

        $products = $query->orderBy('name')->get();
        $categories = Category::all();

        // Calculate totals
        $totals = [
            'total_stock' => $products->sum('stock'),
            'total_value' => $products->sum(function($product) {
                return $product->stock * $product->price;
            }),
            'total_products' => $products->count(),
            'low_stock' => $products->filter(function($product) {
                return $product->stock <= $product->min_stock && $product->stock > 0;
            })->count(),
            'out_of_stock' => $products->filter(function($product) {
                return $product->stock <= 0;
            })->count(),
        ];

        if ($request->ajax()) {
            return response()->json([
                'products' => $products,
                'totals' => $totals
            ]);
        }

        return view('admin.reports.stock', compact('products', 'categories', 'totals'));
    }

    /**
     * Transaction report.
     */
    public function transactions(Request $request)
    {
        $query = Transaction::with(['product', 'user', 'supplier']);

        // Date range filter
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        } elseif ($request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        } elseif ($request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Filter by type
        if ($request->type) {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filter by product
        if ($request->product_id) {
            $query->where('product_id', $request->product_id);
        }

        // Filter by supplier
        if ($request->supplier_id) {
            $query->where('supplier_id', $request->supplier_id);
        }

        $transactions = $query->latest()->get();
        
        // Calculate summary
        $summary = [
            'total_transactions' => $transactions->count(),
            'total_masuk' => $transactions->where('type', 'masuk')->sum('quantity'),
            'total_keluar' => $transactions->where('type', 'keluar')->sum('quantity'),
            'total_value' => $transactions->sum(function($trx) {
                return $trx->quantity * ($trx->product->price ?? 0);
            }),
            'pending' => $transactions->where('status', 'pending')->count(),
            'approved' => $transactions->where('status', 'approved')->count(),
            'rejected' => $transactions->where('status', 'rejected')->count(),
        ];

        // Get data for charts
        $dailyData = Transaction::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw("SUM(CASE WHEN type = 'masuk' THEN quantity ELSE 0 END) as masuk"),
                DB::raw("SUM(CASE WHEN type = 'keluar' THEN quantity ELSE 0 END) as keluar")
            )
            ->when($request->start_date, function($q, $date) {
                $q->whereDate('created_at', '>=', $date);
            })
            ->when($request->end_date, function($q, $date) {
                $q->whereDate('created_at', '<=', $date);
            })
            ->groupBy('date')
            ->orderBy('date')
            ->limit(30)
            ->get();

        $products = Product::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();

        return view('admin.reports.transactions', compact(
            'transactions', 
            'summary', 
            'dailyData',
            'products',
            'suppliers'
        ));
    }

    /**
     * Export stock report to PDF.
     */
    public function exportStockPdf(Request $request)
    {
        $query = Product::with('category');

        if ($request->category) {
            $query->where('category_id', $request->category);
        }

        $products = $query->orderBy('name')->get();
        
        $pdf = Pdf::loadView('admin.reports.pdf.stock', compact('products'));
        
        return $pdf->download('laporan-stok-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Export transaction report to PDF.
     */
    public function exportTransactionPdf(Request $request)
    {
        $query = Transaction::with(['product', 'user', 'supplier']);

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        $transactions = $query->latest()->get();
        
        $pdf = Pdf::loadView('admin.reports.pdf.transactions', compact('transactions'));
        
        return $pdf->download('laporan-transaksi-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Export stock report to Excel (CSV).
     */
    public function exportStockExcel(Request $request)
    {
        $query = Product::with('category');

        if ($request->category) {
            $query->where('category_id', $request->category);
        }

        $products = $query->orderBy('name')->get();

        $filename = "laporan-stok-" . date('Y-m-d') . ".csv";
        $handle = fopen('php://output', 'w');

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        // Header CSV
        fputcsv($handle, [
            'Kode', 
            'Nama Produk', 
            'Kategori', 
            'Stok', 
            'Min Stok', 
            'Satuan', 
            'Harga', 
            'Total Nilai',
            'Status'
        ]);

        // Data
        foreach ($products as $product) {
            fputcsv($handle, [
                $product->code,
                $product->name,
                $product->category->name ?? '-',
                $product->stock,
                $product->min_stock,
                $product->unit,
                $product->price,
                $product->stock * $product->price,
                $product->stock_status
            ]);
        }

        fclose($handle);
        exit;
    }

    /**
     * Export transaction report to Excel (CSV).
     */
    public function exportTransactionExcel(Request $request)
    {
        $query = Transaction::with(['product', 'user', 'supplier']);

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        $transactions = $query->latest()->get();

        $filename = "laporan-transaksi-" . date('Y-m-d') . ".csv";
        $handle = fopen('php://output', 'w');

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        // Header CSV
        fputcsv($handle, [
            'Tanggal',
            'Tipe',
            'Produk',
            'Jumlah',
            'Supplier/Tujuan',
            'User',
            'Status',
            'Referensi',
            'Keterangan'
        ]);

        // Data
        foreach ($transactions as $trx) {
            fputcsv($handle, [
                $trx->created_at->format('d/m/Y H:i'),
                $trx->type,
                $trx->product->name ?? '-',
                $trx->quantity . ' ' . ($trx->product->unit ?? ''),
                $trx->type == 'masuk' ? ($trx->supplier->name ?? '-') : ($trx->destination ?? '-'),
                $trx->user->name ?? '-',
                $trx->status,
                $trx->reference ?? '-',
                $trx->notes ?? '-'
            ]);
        }

        fclose($handle);
        exit;
    }
}