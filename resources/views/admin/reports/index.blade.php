<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan - Asian Agri Inventory</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Font Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50">

    <!-- Navbar -->
    <nav class="bg-[#1E3A2E] text-white shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-2">
                    <i class="fa-solid fa-seedling text-xl"></i>
                    <span class="font-bold text-lg">ASIAN AGRI - ADMIN</span>
                </div>
                
                <div class="flex items-center space-x-4">
                    <span class="text-sm hidden md:block">
                        <i class="fa-regular fa-user mr-2"></i>
                        {{ Auth::user()->name }}
                        <span class="ml-2 px-2 py-1 bg-yellow-600 rounded text-xs">Admin</span>
                    </span>
                    
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white text-sm px-3 py-1.5 rounded-md transition flex items-center gap-1">
                            <i class="fa-solid fa-right-from-bracket"></i>
                            <span class="hidden md:inline">Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        
        <!-- Welcome Message -->
        <div class="mb-8">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">
                Dashboard Laporan
            </h1>
            <p class="text-gray-600 mt-1">
                {{ now()->format('l, d F Y') }} | 
                <span class="text-[#1E3A2E] font-semibold">Analisis data inventory</span>
            </p>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <a href="{{ route('admin.reports.stock') }}" class="bg-white hover:shadow-md rounded-lg p-4 text-center transition border border-gray-200">
                <i class="fa-solid fa-boxes text-blue-600 text-2xl mb-2"></i>
                <p class="text-sm font-medium text-gray-700">Laporan Stok</p>
            </a>
            <a href="{{ route('admin.reports.transactions') }}" class="bg-white hover:shadow-md rounded-lg p-4 text-center transition border border-gray-200">
                <i class="fa-solid fa-clock-rotate-left text-green-600 text-2xl mb-2"></i>
                <p class="text-sm font-medium text-gray-700">Laporan Transaksi</p>
            </a>
            <a href="{{ route('admin.products.index') }}" class="bg-white hover:shadow-md rounded-lg p-4 text-center transition border border-gray-200">
                <i class="fa-solid fa-box text-gray-600 text-2xl mb-2"></i>
                <p class="text-sm font-medium text-gray-700">Kelola Produk</p>
            </a>
            <a href="{{ route('admin.dashboard') }}" class="bg-white hover:shadow-md rounded-lg p-4 text-center transition border border-gray-200">
                <i class="fa-solid fa-home text-[#1E3A2E] text-2xl mb-2"></i>
                <p class="text-sm font-medium text-gray-700">Dashboard</p>
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Total Produk</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $data['totalProducts'] }}</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fa-solid fa-boxes text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Total Transaksi</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $data['totalTransactions'] }}</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fa-solid fa-clock-rotate-left text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Total Kategori</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $data['totalCategories'] }}</p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-full">
                        <i class="fa-solid fa-tags text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Total Supplier</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $data['totalSuppliers'] }}</p>
                    </div>
                    <div class="bg-orange-100 p-3 rounded-full">
                        <i class="fa-solid fa-truck text-orange-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Nilai Stok Card -->
        <div class="bg-gradient-to-r from-[#1E3A2E] to-[#2E7D32] rounded-lg shadow-md p-6 text-white mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm">Total Nilai Stok</p>
                    <p class="text-4xl font-bold mt-2">Rp {{ number_format($data['stockValue'], 0, ',', '.') }}</p>
                    <p class="text-green-100 text-xs mt-2">Berdasarkan harga rata-rata produk</p>
                </div>
                <div class="bg-white bg-opacity-20 p-4 rounded-full">
                    <i class="fa-solid fa-coins text-4xl"></i>
                </div>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Monthly Transactions Chart -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Transaksi Bulanan {{ date('Y') }}</h3>
                <canvas id="monthlyChart" height="250"></canvas>
            </div>
            
            <!-- Stock Summary -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Ringkasan Stok</h3>
                
                @php
                    $totalStock = \App\Models\Product::sum('stock');
                    $lowStock = \App\Models\Product::whereColumn('stock', '<=', 'min_stock')
                                        ->where('stock', '>', 0)
                                        ->count();
                    $outOfStock = \App\Models\Product::where('stock', '<=', 0)->count();
                    $goodStock = \App\Models\Product::whereColumn('stock', '>', 'min_stock')->count();
                @endphp
                
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between text-sm text-gray-600 mb-1">
                            <span>Stok Aman</span>
                            <span>{{ $goodStock }} produk</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-600 h-2 rounded-full" style="width: {{ $goodStock > 0 ? ($goodStock / ($goodStock + $lowStock + $outOfStock) * 100) : 0 }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm text-gray-600 mb-1">
                            <span>Stok Menipis</span>
                            <span>{{ $lowStock }} produk</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-yellow-500 h-2 rounded-full" style="width: {{ $lowStock > 0 ? ($lowStock / ($goodStock + $lowStock + $outOfStock) * 100) : 0 }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm text-gray-600 mb-1">
                            <span>Stok Habis</span>
                            <span>{{ $outOfStock }} produk</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-red-600 h-2 rounded-full" style="width: {{ $outOfStock > 0 ? ($outOfStock / ($goodStock + $lowStock + $outOfStock) * 100) : 0 }}%"></div>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-3 gap-4 mt-6">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-green-600">{{ number_format($totalStock) }}</p>
                        <p class="text-xs text-gray-500">Total Stok (kg)</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-yellow-600">{{ $lowStock }}</p>
                        <p class="text-xs text-gray-500">Menipis</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-red-600">{{ $outOfStock }}</p>
                        <p class="text-xs text-gray-500">Habis</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Reports -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4 bg-blue-50 border-b border-gray-200">
                    <h3 class="font-semibold text-gray-800">
                        <i class="fa-solid fa-boxes mr-2 text-blue-600"></i>
                        Laporan Stok
                    </h3>
                </div>
                <div class="p-6">
                    <p class="text-gray-600 mb-4">Lihat detail stok semua produk, filter berdasarkan kategori dan status.</p>
                    <div class="space-y-2">
                        <a href="{{ route('admin.reports.stock') }}" class="block text-[#1E3A2E] hover:underline">
                            <i class="fa-solid fa-arrow-right mr-2"></i>Lihat Laporan Stok
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4 bg-green-50 border-b border-gray-200">
                    <h3 class="font-semibold text-gray-800">
                        <i class="fa-solid fa-clock-rotate-left mr-2 text-green-600"></i>
                        Laporan Transaksi
                    </h3>
                </div>
                <div class="p-6">
                    <p class="text-gray-600 mb-4">Lihat riwayat semua transaksi masuk dan keluar dengan filter tanggal.</p>
                    <div class="space-y-2">
                        <a href="{{ route('admin.reports.transactions') }}" class="block text-[#1E3A2E] hover:underline">
                            <i class="fa-solid fa-arrow-right mr-2"></i>Lihat Laporan Transaksi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t mt-8 py-4">
        <div class="container mx-auto px-4 text-center text-sm text-gray-500">
            <p>&copy; {{ date('Y') }} Asian Agri. All rights reserved.</p>
            <p class="text-xs mt-1">Inventory System v1.0.0</p>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Monthly chart data from PHP
            const monthlyData = @json($data['monthlyTransactions']);
            
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            const masukData = Array(12).fill(0);
            const keluarData = Array(12).fill(0);
            
            monthlyData.forEach(item => {
                const monthIndex = parseInt(item.month) - 1;
                masukData[monthIndex] = item.total_masuk;
                keluarData[monthIndex] = item.total_keluar;
            });

            const ctx = document.getElementById('monthlyChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: months,
                    datasets: [
                        {
                            label: 'Stok Masuk',
                            data: masukData,
                            backgroundColor: 'rgba(46, 125, 50, 0.5)',
                            borderColor: '#2E7D32',
                            borderWidth: 1
                        },
                        {
                            label: 'Stok Keluar',
                            data: keluarData,
                            backgroundColor: 'rgba(242, 153, 74, 0.5)',
                            borderColor: '#F2994A',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>

</body>
</html>