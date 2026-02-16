<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product; // Nanti akan kita buat
use App\Models\Transaction; // Nanti akan kita buat

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard utama
     */
    public function index()
    {
        // Data statistik sederhana (nanti akan diambil dari database)
        $data = [
            'totalStok' => 1250,
            'stokMasukHariIni' => 150,
            'stokKeluarHariIni' => 75,
            'stokMenipis' => 3,
            'daftarStok' => [
                ['nama' => 'Urea', 'stok' => 500, 'status' => 'Aman', 'warna' => 'green'],
                ['nama' => 'ZA', 'stok' => 300, 'status' => 'Aman', 'warna' => 'green'],
                ['nama' => 'NPK', 'stok' => 15, 'status' => 'Menipis', 'warna' => 'yellow'],
                ['nama' => 'SP-36', 'stok' => 8, 'status' => 'Kritis', 'warna' => 'red'],
                ['nama' => 'KCL', 'stok' => 120, 'status' => 'Aman', 'warna' => 'green'],
            ]
        ];

        return view('dashboard', compact('data'));
    }

    /**
     * Tampilkan dashboard untuk role tertentu (jika perlu)
     */
    public function admin()
    {
        // Dashboard khusus admin dengan data lebih lengkap
        return view('dashboard.admin');
    }

    /**
     * Tampilkan dashboard untuk krani gudang
     */
    public function krani()
    {
        // Dashboard khusus krani (lebih sederhana)
        return view('dashboard.krani');
    }

    /**
     * Ambil data statistik real-time (untuk AJAX)
     */
    public function getStats()
    {
        // Nanti bisa dipanggil via AJAX untuk update real-time
        return response()->json([
            'totalStok' => 1250,
            'stokMasuk' => 150,
            'stokKeluar' => 75,
            'stokMenipis' => 3
        ]);
    }
}