<?php

use Illuminate\Support\Facades\Route;

// AUTH
use App\Http\Controllers\Auth\LoginController;

// ADMIN CONTROLLERS
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==============================
// AUTH
// ==============================

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.process');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


// ==============================
// ADMIN AREA
// ==============================

Route::prefix('admin')
    ->name('admin.')
    ->middleware('auth')
    ->group(function () {

        // DASHBOARD
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');
            
        // REALTIME DATA API (TAMBAHKAN INI!)
        Route::get('/realtime-data', [DashboardController::class, 'getRealtimeData'])
            ->name('realtime');

        // MASTER DATA
        Route::resource('products', ProductController::class);
        Route::resource('suppliers', SupplierController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('users', UserController::class);

        // TRANSACTIONS
        Route::prefix('transactions')->name('transactions.')->group(function () {
            Route::get('/', [TransactionController::class, 'index'])->name('index');
            Route::get('/pending', [TransactionController::class, 'pending'])->name('pending');
            Route::post('/{id}/approve', [TransactionController::class, 'approve'])->name('approve');
            Route::post('/{id}/reject', [TransactionController::class, 'reject'])->name('reject');
        });

        // REPORTS
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/stock', [ReportController::class, 'stock'])->name('reports.stock');
        Route::get('/reports/transactions', [ReportController::class, 'transactions'])->name('reports.transactions');
    });