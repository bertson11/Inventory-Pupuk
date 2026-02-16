<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Route untuk halaman utama (redirect ke login atau dashboard)
Route::get('/', function () {
    if (\Illuminate\Support\Facades\Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// Route untuk guest (belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// Route untuk yang sudah login
Route::middleware('auth')->group(function () {
    
    // Dashboard utama
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Dashboard berdasarkan role (optional)
    Route::get('/dashboard/admin', [DashboardController::class, 'admin'])->name('dashboard.admin');
    Route::get('/dashboard/krani', [DashboardController::class, 'krani'])->name('dashboard.krani');
    
    // API endpoint untuk data real-time
    Route::get('/api/dashboard/stats', [DashboardController::class, 'getStats'])->name('api.dashboard.stats');
    
    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// Route fallback untuk 404
Route::fallback(function () {
    return view('errors.404');
});