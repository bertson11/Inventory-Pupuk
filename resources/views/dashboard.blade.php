<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Asian Agri Inventory</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Font Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50">

    <!-- Navbar -->
    <nav class="bg-[#1E3A2E] text-white shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center space-x-2">
                    <i class="fa-solid fa-seedling text-xl"></i>
                    <span class="font-bold text-lg">ASIAN AGRI</span>
                </div>
                
                <!-- User Menu -->
                <div class="flex items-center space-x-4">
                    <span class="text-sm hidden md:block">
                        <i class="fa-regular fa-user mr-2"></i>
                        {{ Auth::user()->name ?? 'Admin' }}
                    </span>
                    
                    <!-- Logout Button -->
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
                Selamat Datang, {{ Auth::user()->name ?? 'Admin' }}!
            </h1>
            <p class="text-gray-600 mt-1">
                {{ now()->format('l, d F Y') }}
            </p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Stok -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-[#1E3A2E]">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm">Total Stok</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1">{{ number_format($data['totalStok']) }} kg</p>
                    </div>
                    <div class="bg-[#1E3A2E] bg-opacity-10 p-3 rounded-full">
                        <i class="fa-solid fa-warehouse text-[#1E3A2E] text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Stok Masuk Hari Ini -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm">Stok Masuk Hari Ini</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1">{{ number_format($data['stokMasukHariIni']) }} kg</p>
                    </div>
                    <div class="bg-green-500 bg-opacity-10 p-3 rounded-full">
                        <i class="fa-solid fa-arrow-down text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Stok Keluar Hari Ini -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-orange-500">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm">Stok Keluar Hari Ini</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1">{{ number_format($data['stokKeluarHariIni']) }} kg</p>
                    </div>
                    <div class="bg-orange-500 bg-opacity-10 p-3 rounded-full">
                        <i class="fa-solid fa-arrow-up text-orange-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Stok Menipis -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm">Stok Menipis</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1">{{ $data['stokMenipis'] }} item</p>
                    </div>
                    <div class="bg-red-500 bg-opacity-10 p-3 rounded-full">
                        <i class="fa-solid fa-triangle-exclamation text-red-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <a href="#" class="bg-white hover:shadow-md rounded-lg p-4 text-center transition border border-gray-200">
                <i class="fa-solid fa-cart-plus text-[#1E3A2E] text-2xl mb-2"></i>
                <p class="text-sm font-medium text-gray-700">Stok Masuk</p>
            </a>
            <a href="#" class="bg-white hover:shadow-md rounded-lg p-4 text-center transition border border-gray-200">
                <i class="fa-solid fa-cart-arrow-down text-orange-600 text-2xl mb-2"></i>
                <p class="text-sm font-medium text-gray-700">Stok Keluar</p>
            </a>
            <a href="#" class="bg-white hover:shadow-md rounded-lg p-4 text-center transition border border-gray-200">
                <i class="fa-solid fa-boxes text-blue-600 text-2xl mb-2"></i>
                <p class="text-sm font-medium text-gray-700">Daftar Pupuk</p>
            </a>
            <a href="#" class="bg-white hover:shadow-md rounded-lg p-4 text-center transition border border-gray-200">
                <i class="fa-solid fa-file-lines text-purple-600 text-2xl mb-2"></i>
                <p class="text-sm font-medium text-gray-700">Laporan</p>
            </a>
        </div>

        <!-- Tabel Stok -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="font-semibold text-gray-800">Daftar Stok Pupuk</h3>
                <span class="text-sm text-gray-500">Total: {{ count($data['daftarStok']) }} item</span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Pupuk</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok (kg)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($data['daftarStok'] as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-800">{{ $item['nama'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ number_format($item['stok']) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'Aman' => 'bg-green-100 text-green-800',
                                        'Menipis' => 'bg-yellow-100 text-yellow-800',
                                        'Kritis' => 'bg-red-100 text-red-800'
                                    ];
                                    $colorClass = $statusColors[$item['status']] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="px-2 py-1 text-xs rounded-full {{ $colorClass }}">
                                    {{ $item['status'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button class="text-blue-600 hover:text-blue-800 mr-3" title="Edit">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </button>
                                <button class="text-green-600 hover:text-green-800" title="Tambah Stok">
                                    <i class="fa-solid fa-plus"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination (placeholder) -->
            <div class="px-6 py-4 border-t border-gray-200 flex justify-between items-center">
                <p class="text-sm text-gray-500">Menampilkan 1-5 dari 5 item</p>
                <div class="flex space-x-2">
                    <button class="px-3 py-1 border rounded text-gray-500" disabled>Prev</button>
                    <button class="px-3 py-1 border rounded bg-[#1E3A2E] text-white">1</button>
                    <button class="px-3 py-1 border rounded text-gray-500" disabled>Next</button>
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

</body>
</html>