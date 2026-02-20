<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Stok - Asian Agri Inventory</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Font Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            margin-bottom: 1rem;
            color: #4B5563;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #1E3A2E !important;
            color: white !important;
            border: none;
        }
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
                Laporan Stok
            </h1>
            <p class="text-gray-600 mt-1">
                {{ now()->format('l, d F Y') }} | 
                <span class="text-[#1E3A2E] font-semibold">Data stok semua produk</span>
            </p>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
            <a href="{{ route('admin.reports.index') }}" class="bg-white hover:shadow-md rounded-lg p-4 text-center transition border border-gray-200">
                <i class="fa-solid fa-chart-pie text-gray-600 text-2xl mb-2"></i>
                <p class="text-sm font-medium text-gray-700">Dashboard</p>
            </a>
            <a href="{{ route('admin.reports.transactions') }}" class="bg-white hover:shadow-md rounded-lg p-4 text-center transition border border-gray-200">
                <i class="fa-solid fa-clock-rotate-left text-green-600 text-2xl mb-2"></i>
                <p class="text-sm font-medium text-gray-700">Transaksi</p>
            </a>
            <a href="{{ route('admin.reports.stock') }}?export=excel" class="bg-white hover:shadow-md rounded-lg p-4 text-center transition border border-gray-200">
                <i class="fa-solid fa-file-excel text-green-600 text-2xl mb-2"></i>
                <p class="text-sm font-medium text-gray-700">Export Excel</p>
            </a>
            <a href="{{ route('admin.reports.stock') }}?export=pdf" class="bg-white hover:shadow-md rounded-lg p-4 text-center transition border border-gray-200">
                <i class="fa-solid fa-file-pdf text-red-600 text-2xl mb-2"></i>
                <p class="text-sm font-medium text-gray-700">Export PDF</p>
            </a>
            <a href="{{ route('admin.dashboard') }}" class="bg-white hover:shadow-md rounded-lg p-4 text-center transition border border-gray-200">
                <i class="fa-solid fa-home text-[#1E3A2E] text-2xl mb-2"></i>
                <p class="text-sm font-medium text-gray-700">Dashboard</p>
            </a>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <p class="text-gray-500 text-sm">Total Produk</p>
                <p class="text-2xl font-bold">{{ $totals['total_products'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6">
                <p class="text-gray-500 text-sm">Total Stok</p>
                <p class="text-2xl font-bold">{{ number_format($totals['total_stock']) }} kg</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6">
                <p class="text-gray-500 text-sm">Stok Menipis</p>
                <p class="text-2xl font-bold text-yellow-600">{{ $totals['low_stock'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6">
                <p class="text-gray-500 text-sm">Stok Habis</p>
                <p class="text-2xl font-bold text-red-600">{{ $totals['out_of_stock'] }}</p>
            </div>
        </div>

        <!-- Filter Form -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h3 class="font-semibold text-gray-800 mb-4">Filter Laporan</h3>
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-gray-600 text-sm mb-1">Kategori</label>
                    <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1E3A2E]">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-gray-600 text-sm mb-1">Status Stok</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1E3A2E]">
                        <option value="">Semua</option>
                        <option value="aman" {{ request('status') == 'aman' ? 'selected' : '' }}>Aman</option>
                        <option value="menipis" {{ request('status') == 'menipis' ? 'selected' : '' }}>Menipis</option>
                        <option value="kritis" {{ request('status') == 'kritis' ? 'selected' : '' }}>Kritis</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-600 text-sm mb-1">Cari</label>
                    <input type="text" name="search" placeholder="Nama/kode produk..." 
                           value="{{ request('search') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1E3A2E]">
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="bg-[#1E3A2E] hover:bg-[#2E7D32] text-white px-4 py-2 rounded-md w-full">
                        <i class="fa-solid fa-filter mr-2"></i>Filter
                    </button>
                    <a href="{{ route('admin.reports.stock') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Tabel Stok -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="font-semibold text-gray-800">
                    <i class="fa-solid fa-boxes mr-2 text-[#1E3A2E]"></i>
                    Detail Stok Produk
                </h3>
            </div>
            
            <div class="p-6">
                <table id="stockTable" class="w-full">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>Stok</th>
                            <th>Min Stok</th>
                            <th>Satuan</th>
                            <th>Harga</th>
                            <th>Total Nilai</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td>{{ $product->code }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->category->name ?? '-' }}</td>
                            <td data-order="{{ $product->stock }}">{{ number_format($product->stock) }}</td>
                            <td data-order="{{ $product->min_stock }}">{{ $product->min_stock }}</td>
                            <td>{{ $product->unit }}</td>
                            <td data-order="{{ $product->price }}">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                            <td data-order="{{ $product->stock * $product->price }}">
                                Rp {{ number_format($product->stock * $product->price, 0, ',', '.') }}
                            </td>
                            <td>
                                @php
                                    $statusClass = [
                                        'Aman' => 'bg-green-100 text-green-800',
                                        'Menipis' => 'bg-yellow-100 text-yellow-800',
                                        'Kritis' => 'bg-red-100 text-red-800'
                                    ][$product->stock_status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="px-2 py-1 text-xs rounded-full {{ $statusClass }}">
                                    {{ $product->stock_status }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" style="text-align:right">Total:</th>
                            <th>{{ number_format($totals['total_stock']) }}</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>Rp {{ number_format($totals['total_value'], 0, ',', '.') }}</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
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
        $(document).ready(function() {
            $('#stockTable').DataTable({
                pageLength: 25,
                order: [[1, 'asc']],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                },
                footerCallback: function(row, data, start, end, display) {
                    // Hitung total untuk kolom stok dan nilai
                    let api = this.api();
                    
                    // Total stok
                    let totalStok = api
                        .column(3)
                        .data()
                        .reduce(function(a, b) {
                            return parseFloat(a) + parseFloat(b);
                        }, 0);
                    
                    // Total nilai
                    let totalNilai = api
                        .column(7)
                        .data()
                        .reduce(function(a, b) {
                            let nilai = parseFloat(b.replace(/[^0-9,-]/g, '').replace(',', ''));
                            return a + nilai;
                        }, 0);
                    
                    // Update footer
                    $(api.column(3).footer()).html(totalStok.toLocaleString('id-ID'));
                    $(api.column(7).footer()).html('Rp ' + totalNilai.toLocaleString('id-ID'));
                }
            });
        });
    </script>

</body>
</html>