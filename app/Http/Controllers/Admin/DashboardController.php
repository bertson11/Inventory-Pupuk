<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\Category;
use App\Models\Supplier;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard berdasarkan role user
     */
    public function index()
{
    $user = Auth::user();
    
    // Data statistik realtime dari database
    $data = [
        'totalStok' => Product::sum('stock'),
        'totalProduk' => Product::count(),
        'totalUser' => DB::table('users')->count(),
        'totalKategori' => Category::count(),
        'totalSupplier' => Supplier::count(),
        
        'stokMasukHariIni' => Transaction::where('type', 'masuk')
                                ->whereDate('created_at', today())
                                ->sum('quantity'),
        
        'stokKeluarHariIni' => Transaction::where('type', 'keluar')
                                ->whereDate('created_at', today())
                                ->sum('quantity'),
        
        'stokMenipis' => Product::whereColumn('stock', '<=', 'min_stock')->count(),
        'pendingApproval' => Transaction::where('status', 'pending')->count(),
        
        'daftarStok' => Product::with('category')
                            ->orderBy('name')
                            ->get()
                            ->map(function($product) {
                                return [
                                    'id' => $product->id,
                                    'nama' => $product->name,
                                    'kode' => $product->code,
                                    'kategori' => $product->category->name ?? 'Tanpa Kategori',
                                    'stok' => $product->stock,
                                    'unit' => $product->unit,
                                    'status' => $this->getStockStatus($product->stock, $product->min_stock),
                                    'min_stock' => $product->min_stock,
                                ];
                            }),
        
        'transaksiTerbaru' => Transaction::with('product', 'user')
                                ->latest()
                                ->limit(10)
                                ->get(),
    ];

    // Redirect berdasarkan role ke VIEW yang berbeda
    if ($user->role == 'admin') {
        return view('admin.dashboard', compact('data'));
    } elseif ($user->role == 'ktu') {
        return view('ktu.dashboard', compact('data'));
    } else {
        return view('krani.dashboard', compact('data'));
    }
}

    /**
     * Dashboard untuk Admin
     */
    public function adminDashboard()
    {
        // Data statistik realtime dari database
        $data = [
            'totalStok' => Product::sum('stock'),
            'totalProduk' => Product::count(),
            'totalUser' => DB::table('users')->count(),
            'totalKategori' => Category::count(),
            'totalSupplier' => Supplier::count(),
            
            // Stok masuk hari ini
            'stokMasukHariIni' => Transaction::where('type', 'masuk')
                                    ->whereDate('created_at', today())
                                    ->sum('quantity'),
            
            // Stok keluar hari ini
            'stokKeluarHariIni' => Transaction::where('type', 'keluar')
                                    ->whereDate('created_at', today())
                                    ->sum('quantity'),
            
            // Jumlah produk dengan stok menipis (di bawah min_stock)
            'stokMenipis' => Product::whereColumn('stock', '<=', 'min_stock')->count(),
            
            // Jumlah pending approval
            'pendingApproval' => Transaction::where('status', 'pending')->count(),
            
            // Daftar stok untuk tabel
            'daftarStok' => Product::with('category')
                                ->orderBy('name')
                                ->get()
                                ->map(function($product) {
                                    return [
                                        'id' => $product->id,
                                        'nama' => $product->name,
                                        'kode' => $product->code,
                                        'kategori' => $product->category->name ?? 'Tanpa Kategori',
                                        'stok' => $product->stock,
                                        'unit' => $product->unit,
                                        'status' => $this->getStockStatus($product->stock, $product->min_stock),
                                        'min_stock' => $product->min_stock,
                                    ];
                                }),
            
            // Transaksi terbaru
            'transaksiTerbaru' => Transaction::with('product', 'user')
                                    ->latest()
                                    ->limit(10)
                                    ->get(),
        ];

        return view('admin.dashboard', compact('data'));
    }

    /**
     * Dashboard untuk KTU
     */
    public function ktuDashboard()
    {
        $data = [
            'totalStok' => Product::sum('stock'),
            'pendingApproval' => Transaction::where('status', 'pending')->count(),
            'approvedHariIni' => Transaction::where('status', 'approved')
                                    ->whereDate('updated_at', today())
                                    ->count(),
            'stokMenipis' => Product::whereColumn('stock', '<=', 'min_stock')->count(),
            
            // Daftar pending approval
            'pendingTransactions' => Transaction::with('product', 'user')
                                        ->where('status', 'pending')
                                        ->latest()
                                        ->get(),
            
            'daftarStok' => Product::with('category')
                                ->orderBy('name')
                                ->get()
                                ->map(function($product) {
                                    return [
                                        'id' => $product->id,
                                        'nama' => $product->name,
                                        'stok' => $product->stock,
                                        'unit' => $product->unit,
                                        'status' => $this->getStockStatus($product->stock, $product->min_stock),
                                    ];
                                }),
        ];

        return view('dashboard.ktu', compact('data'));
    }

    /**
     * Dashboard untuk Krani
     */
    public function kraniDashboard()
    {
        $userId = Auth::id();
        
        $data = [
            'totalStok' => Product::sum('stock'),
            'stokMasukHariIni' => Transaction::where('type', 'masuk')
                                    ->where('user_id', $userId)
                                    ->whereDate('created_at', today())
                                    ->sum('quantity'),
            'stokKeluarHariIni' => Transaction::where('type', 'keluar')
                                    ->where('user_id', $userId)
                                    ->whereDate('created_at', today())
                                    ->sum('quantity'),
            
            // Daftar produk untuk quick input
            'products' => Product::orderBy('name')->get(['id', 'name', 'stock', 'unit']),
            
            // Riwayat transaksi user
            'riwayat' => Transaction::with('product')
                            ->where('user_id', $userId)
                            ->latest()
                            ->limit(10)
                            ->get(),
        ];

        return view('dashboard.krani', compact('data'));
    }

    /**
     * Helper untuk menentukan status stok
     */
    private function getStockStatus($stock, $minStock)
    {
        if ($stock <= 0) return 'Kritis';
        if ($stock <= $minStock) return 'Menipis';
        return 'Aman';
    }

    /**
     * API untuk data realtime (via AJAX)
     */
    public function getRealtimeData()
    {
        $user = Auth::user();
        
        if ($user->role == 'admin') {
            $data = [
                'totalStok' => Product::sum('stock'),
                'stokMasukHariIni' => Transaction::where('type', 'masuk')
                                        ->whereDate('created_at', today())
                                        ->sum('quantity'),
                'stokKeluarHariIni' => Transaction::where('type', 'keluar')
                                        ->whereDate('created_at', today())
                                        ->sum('quantity'),
                'stokMenipis' => Product::whereColumn('stock', '<=', 'min_stock')->count(),
                'pendingApproval' => Transaction::where('status', 'pending')->count(),
            ];
        } elseif ($user->role == 'ktu') {
            $data = [
                'pendingApproval' => Transaction::where('status', 'pending')->count(),
                'approvedHariIni' => Transaction::where('status', 'approved')
                                        ->whereDate('updated_at', today())
                                        ->count(),
            ];
        } else {
            $data = [
                'stokMasukHariIni' => Transaction::where('type', 'masuk')
                                        ->where('user_id', $user->id)
                                        ->whereDate('created_at', today())
                                        ->sum('quantity'),
                'stokKeluarHariIni' => Transaction::where('type', 'keluar')
                                        ->where('user_id', $user->id)
                                        ->whereDate('created_at', today())
                                        ->sum('quantity'),
            ];
        }
        
        return response()->json($data);
    }
}