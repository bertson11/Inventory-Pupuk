<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Asian Agri Inventory</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Font Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- jQuery (for AJAX) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        .realtime-update { transition: all 0.3s ease; }
        .realtime-update.highlight { background-color: rgba(46, 125, 50, 0.1); }
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
                Selamat Datang, {{ Auth::user()->name }}!
            </h1>
            <p class="text-gray-600 mt-1">
                {{ now()->format('l, d F Y') }} | 
                <span class="text-[#1E3A2E] font-semibold" id="realtime-clock"></span>
            </p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Stok -->
            <div class="stat-card bg-white rounded-lg shadow-md p-6 border-l-4 border-[#1E3A2E]" id="stat-total-stok">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm">Total Stok</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1" id="total-stok">{{ number_format($data['totalStok']) }}</p>
                        <p class="text-xs text-gray-400 mt-1">kg</p>
                    </div>
                    <div class="bg-[#1E3A2E] bg-opacity-10 p-3 rounded-full">
                        <i class="fa-solid fa-warehouse text-[#1E3A2E] text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Stok Masuk Hari Ini -->
            <div class="stat-card bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500" id="stat-masuk">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm">Stok Masuk Hari Ini</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1" id="stok-masuk">{{ number_format($data['stokMasukHariIni']) }}</p>
                        <p class="text-xs text-gray-400 mt-1">kg</p>
                    </div>
                    <div class="bg-green-500 bg-opacity-10 p-3 rounded-full">
                        <i class="fa-solid fa-arrow-down text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Stok Keluar Hari Ini -->
            <div class="stat-card bg-white rounded-lg shadow-md p-6 border-l-4 border-orange-500" id="stat-keluar">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm">Stok Keluar Hari Ini</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1" id="stok-keluar">{{ number_format($data['stokKeluarHariIni']) }}</p>
                        <p class="text-xs text-gray-400 mt-1">kg</p>
                    </div>
                    <div class="bg-orange-500 bg-opacity-10 p-3 rounded-full">
                        <i class="fa-solid fa-arrow-up text-orange-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Stok Menipis -->
            <div class="stat-card bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500" id="stat-menipis">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm">Stok Menipis</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1" id="stok-menipis">{{ $data['stokMenipis'] }}</p>
                        <p class="text-xs text-gray-400 mt-1">item</p>
                    </div>
                    <div class="bg-red-500 bg-opacity-10 p-3 rounded-full">
                        <i class="fa-solid fa-triangle-exclamation text-red-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Stats for Admin -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Total Produk</p>
                        <p class="text-2xl font-bold">{{ $data['totalProduk'] }}</p>
                    </div>
                    <i class="fa-solid fa-boxes text-3xl text-blue-500"></i>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Total User</p>
                        <p class="text-2xl font-bold">{{ $data['totalUser'] }}</p>
                    </div>
                    <i class="fa-solid fa-users text-3xl text-purple-500"></i>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Pending Approval</p>
                        <p class="text-2xl font-bold text-orange-600" id="pending-count">{{ $data['pendingApproval'] }}</p>
                    </div>
                    <i class="fa-solid fa-clock text-3xl text-orange-500"></i>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <a href="{{ route('admin.products.index') }}" class="bg-white hover:shadow-md rounded-lg p-4 text-center transition border border-gray-200">
                <i class="fa-solid fa-boxes text-[#1E3A2E] text-2xl mb-2"></i>
                <p class="text-sm font-medium text-gray-700">Kelola Produk</p>
            </a>
            <a href="{{ route('admin.categories.index') }}" class="bg-white hover:shadow-md rounded-lg p-4 text-center transition border border-gray-200">
                <i class="fa-solid fa-tags text-blue-600 text-2xl mb-2"></i>
                <p class="text-sm font-medium text-gray-700">Kategori</p>
            </a>
            <a href="{{ route('admin.suppliers.index') }}" class="bg-white hover:shadow-md rounded-lg p-4 text-center transition border border-gray-200">
                <i class="fa-solid fa-truck text-orange-600 text-2xl mb-2"></i>
                <p class="text-sm font-medium text-gray-700">Supplier</p>
            </a>
            <a href="{{ route('admin.users.index') }}" class="bg-white hover:shadow-md rounded-lg p-4 text-center transition border border-gray-200">
                <i class="fa-solid fa-user-plus text-purple-600 text-2xl mb-2"></i>
                <p class="text-sm font-medium text-gray-700">Manajemen User</p>
            </a>
            <a href="{{ route('admin.transactions.pending') }}" class="bg-white hover:shadow-md rounded-lg p-4 text-center transition border border-gray-200">
                <i class="fa-solid fa-check-double text-yellow-600 text-2xl mb-2"></i>
                <p class="text-sm font-medium text-gray-700">Approval</p>
                @if($data['pendingApproval'] > 0)
                    <span class="inline-block bg-red-500 text-white text-xs px-2 py-1 rounded-full mt-1">
                        {{ $data['pendingApproval'] }} pending
                    </span>
                @endif
            </a>
            <a href="{{ route('admin.reports.index') }}" class="bg-white hover:shadow-md rounded-lg p-4 text-center transition border border-gray-200">
                <i class="fa-solid fa-file-lines text-gray-600 text-2xl mb-2"></i>
                <p class="text-sm font-medium text-gray-700">Laporan</p>
            </a>
            <a href="{{ route('admin.transactions.index') }}" class="bg-white hover:shadow-md rounded-lg p-4 text-center transition border border-gray-200">
                <i class="fa-solid fa-clock-rotate-left text-gray-600 text-2xl mb-2"></i>
                <p class="text-sm font-medium text-gray-700">Semua Transaksi</p>
            </a>
            <a href="#" onclick="refreshData()" class="bg-white hover:shadow-md rounded-lg p-4 text-center transition border border-gray-200">
                <i class="fa-solid fa-rotate text-green-600 text-2xl mb-2"></i>
                <p class="text-sm font-medium text-gray-700">Refresh Data</p>
            </a>
        </div>

        <!-- Tabel Stok -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="font-semibold text-gray-800">Daftar Stok Pupuk</h3>
                <a href="{{ route('admin.products.create') }}" class="bg-[#1E3A2E] hover:bg-[#2E7D32] text-white px-4 py-2 rounded-md text-sm">
                    <i class="fa-solid fa-plus mr-1"></i> Tambah Produk
                </a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Pupuk</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Min Stok</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200" id="product-table-body">
                        @foreach($data['daftarStok'] as $item)
                        <tr class="hover:bg-gray-50" id="product-{{ $item['id'] }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $item['kode'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-800">{{ $item['nama'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $item['kategori'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="stock-value font-semibold" data-product="{{ $item['id'] }}">{{ number_format($item['stok']) }}</span>
                                <span class="text-xs text-gray-500">{{ $item['unit'] }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $item['min_stock'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'Aman' => 'bg-green-100 text-green-800',
                                        'Menipis' => 'bg-yellow-100 text-yellow-800',
                                        'Kritis' => 'bg-red-100 text-red-800'
                                    ];
                                    $colorClass = $statusColors[$item['status']] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="px-2 py-1 text-xs rounded-full {{ $colorClass }} status-badge" data-product="{{ $item['id'] }}">
                                    {{ $item['status'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('admin.products.edit', $item['id']) }}" class="text-blue-600 hover:text-blue-800 mr-3" title="Edit">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </a>
                                <button onclick="showAddStockModal({{ $item['id'] }}, '{{ $item['nama'] }}')" 
                                        class="text-green-600 hover:text-green-800 mr-3" title="Tambah Stok">
                                    <i class="fa-solid fa-plus"></i>
                                </button>
                                <button onclick="deleteProduct({{ $item['id'] }}, '{{ $item['nama'] }}')" 
                                        class="text-red-600 hover:text-red-800" title="Hapus">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="font-semibold text-gray-800">Transaksi Terbaru</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Waktu</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produk</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($data['transaksiTerbaru'] as $trx)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $trx->created_at->format('H:i, d/m') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $trx->product->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($trx->type == 'masuk')
                                    <span class="text-green-600"><i class="fa-solid fa-arrow-down mr-1"></i>Masuk</span>
                                @else
                                    <span class="text-orange-600"><i class="fa-solid fa-arrow-up mr-1"></i>Keluar</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ number_format($trx->quantity) }} kg</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $trx->user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusBadge = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'approved' => 'bg-green-100 text-green-800',
                                        'rejected' => 'bg-red-100 text-red-800'
                                    ][$trx->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="px-2 py-1 text-xs rounded-full {{ $statusBadge }}">
                                    {{ $trx->status }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Stok -->
    <div id="addStockModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-8 max-w-md w-full">
            <h3 class="text-xl font-bold mb-4" id="modalTitle">Tambah Stok</h3>
            <form id="addStockForm">
                @csrf
                <input type="hidden" id="product_id" name="product_id">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Jumlah (kg)</label>
                    <input type="number" name="quantity" id="quantity" 
                           class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-[#1E3A2E]"
                           min="1" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Supplier</label>
                    <select name="supplier_id" class="w-full px-3 py-2 border rounded-lg">
                        <option value="">Pilih Supplier</option>
                        @foreach($data['suppliers'] ?? [] as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Keterangan</label>
                    <textarea name="notes" rows="2" class="w-full px-3 py-2 border rounded-lg"></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-300 rounded-lg">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-[#1E3A2E] text-white rounded-lg">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript untuk realtime updates -->
    <script>
        let realtimeInterval;
        
        $(document).ready(function() {
            // Start realtime updates every 30 seconds
            startRealtimeUpdates();
            
            // Update clock every second
            updateClock();
            setInterval(updateClock, 1000);
            
            // Handle form submit
            $('#addStockForm').on('submit', function(e) {
                e.preventDefault();
                addStock();
            });
        });

        function updateClock() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID', { 
                hour: '2-digit', 
                minute: '2-digit',
                second: '2-digit'
            });
            $('#realtime-clock').text(timeString);
        }

        function startRealtimeUpdates() {
            // Update immediately
            fetchRealtimeData();
            
            // Then every 30 seconds
            realtimeInterval = setInterval(fetchRealtimeData, 30000);
        }

        function fetchRealtimeData() {
            $.ajax({
                url: '{{ route("admin.realtime") }}',
                method: 'GET',
                success: function(response) {
                    updateStats(response);
                    highlightUpdatedCards();
                },
                error: function(xhr) {
                    console.error('Failed to fetch realtime data:', xhr);
                }
            });
        }

        function updateStats(data) {
            // Update stat cards
            $('#total-stok').text(formatNumber(data.totalStok));
            $('#stok-masuk').text(formatNumber(data.stokMasukHari