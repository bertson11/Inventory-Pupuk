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

// KTU CONTROLLERS
use App\Http\Controllers\KTU\DashboardController as KTUDashboardController;
use App\Http\Controllers\KTU\ApprovalController;

// KRANI CONTROLLERS
use App\Http\Controllers\Krani\DashboardController as KraniDashboardController;
use App\Http\Controllers\Krani\StockController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==============================
// PUBLIC ROUTES
// ==============================

Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        return redirect()->route($user->role . '.dashboard');
    }
    return redirect()->route('login');
});

// ==============================
// AUTH ROUTES
// ==============================

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.process');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// ==============================
// ADMIN AREA (Role: admin)
// ==============================

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {

        // DASHBOARD
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // REALTIME DATA API
        Route::get('/realtime-data', [DashboardController::class, 'getRealtimeData'])->name('realtime');

        // MASTER DATA (Full CRUD)
        Route::resource('products', ProductController::class);
        Route::post('/products/{id}/update-stock', [ProductController::class, 'updateStock'])->name('products.update-stock');
        
        Route::resource('suppliers', SupplierController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('users', UserController::class);
        
        // User Management Additional Routes
        Route::post('/users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle-active');
        Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');

        // TRANSACTIONS
        Route::prefix('transactions')->name('transactions.')->group(function () {
            Route::get('/', [TransactionController::class, 'index'])->name('index');
            Route::get('/stock-in/create', [TransactionController::class, 'createStockIn'])->name('stock-in.create');
            Route::post('/stock-in', [TransactionController::class, 'storeStockIn'])->name('stock-in.store');
            Route::get('/stock-out/create', [TransactionController::class, 'createStockOut'])->name('stock-out.create');
            Route::post('/stock-out', [TransactionController::class, 'storeStockOut'])->name('stock-out.store');
            Route::get('/pending', [TransactionController::class, 'pending'])->name('pending');
            Route::get('/{id}', [TransactionController::class, 'show'])->name('show');
            Route::post('/{id}/approve', [TransactionController::class, 'approve'])->name('approve');
            Route::post('/{id}/reject', [TransactionController::class, 'reject'])->name('reject');
        });

        // REPORTS
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('index');
            Route::get('/stock', [ReportController::class, 'stock'])->name('stock');
            Route::get('/transactions', [ReportController::class, 'transactions'])->name('transactions');
            Route::get('/export/stock', [ReportController::class, 'exportStockExcel'])->name('export.stock');
            Route::get('/export/transactions', [ReportController::class, 'exportTransactionExcel'])->name('export.transactions');
        });
    });

// ==============================
// KTU AREA (Role: ktu)
// ==============================

Route::prefix('ktu')
    ->name('ktu.')
    ->middleware(['auth', 'role:ktu'])
    ->group(function () {

        // DASHBOARD
        Route::get('/dashboard', [KTUDashboardController::class, 'index'])->name('dashboard');
        
        // APPROVALS
        Route::prefix('approvals')->name('approvals.')->group(function () {
            Route::get('/', [ApprovalController::class, 'index'])->name('index');
            Route::get('/pending', [ApprovalController::class, 'pending'])->name('pending');
            Route::post('/{id}/approve', [ApprovalController::class, 'approve'])->name('approve');
            Route::post('/{id}/reject', [ApprovalController::class, 'reject'])->name('reject');
        });

        // REPORTS (View Only)
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/stock', [ReportController::class, 'stock'])->name('stock');
            Route::get('/transactions', [ReportController::class, 'transactions'])->name('transactions');
        });

        // STOCK MONITORING
        Route::get('/stock-monitoring', [KTUDashboardController::class, 'stockMonitoring'])->name('stock.monitoring');
    });

// ==============================
// KRANI AREA (Role: krani)
// ==============================

Route::prefix('krani')
    ->name('krani.')
    ->middleware(['auth', 'role:krani'])
    ->group(function () {

        // DASHBOARD
        Route::get('/dashboard', [KraniDashboardController::class, 'index'])->name('dashboard');

        // STOCK IN
        Route::prefix('stock-in')->name('stock-in.')->group(function () {
            Route::get('/', [StockController::class, 'createStockIn'])->name('create');
            Route::post('/', [StockController::class, 'storeStockIn'])->name('store');
        });

        // STOCK OUT
        Route::prefix('stock-out')->name('stock-out.')->group(function () {
            Route::get('/', [StockController::class, 'createStockOut'])->name('create');
            Route::post('/', [StockController::class, 'storeStockOut'])->name('store');
        });

        // HISTORY
        Route::get('/history', [StockController::class, 'history'])->name('history');

        // PRODUCT LIST (View Only)
        Route::get('/products', [StockController::class, 'products'])->name('products');
        Route::get('/products/{id}', [StockController::class, 'productDetail'])->name('products.detail');
    });

// ==============================
// FALLBACK ROUTE (404)
// ==============================

Route::fallback(function () {
    return view('errors.404');
});