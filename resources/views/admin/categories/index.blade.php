<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Kategori - Asian Agri Inventory</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Font Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
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
                Manajemen Kategori
            </h1>
            <p class="text-gray-600 mt-1">
                {{ now()->format('l, d F Y') }} | 
                <span class="text-[#1E3A2E] font-semibold">Kelola kategori produk pupuk</span>
            </p>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <a href="{{ route('admin.categories.index') }}" class="bg-white hover:shadow-md rounded-lg p-4 text-center transition border border-gray-200 border-l-4 border-[#1E3A2E]">
                <i class="fa-solid fa-tags text-[#1E3A2E] text-2xl mb-2"></i>
                <p class="text-sm font-medium text-gray-700">Semua Kategori</p>
            </a>
            <a href="{{ route('admin.categories.create') }}" class="bg-white hover:shadow-md rounded-lg p-4 text-center transition border border-gray-200">
                <i class="fa-solid fa-plus-circle text-green-600 text-2xl mb-2"></i>
                <p class="text-sm font-medium text-gray-700">Tambah Kategori</p>
            </a>
            <a href="{{ route('admin.products.index') }}" class="bg-white hover:shadow-md rounded-lg p-4 text-center transition border border-gray-200">
                <i class="fa-solid fa-boxes text-blue-600 text-2xl mb-2"></i>
                <p class="text-sm font-medium text-gray-700">Produk</p>
            </a>
            <a href="{{ route('admin.dashboard') }}" class="bg-white hover:shadow-md rounded-lg p-4 text-center transition border border-gray-200">
                <i class="fa-solid fa-home text-gray-600 text-2xl mb-2"></i>
                <p class="text-sm font-medium text-gray-700">Dashboard</p>
            </a>
        </div>

        <!-- Search & Filter -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h3 class="font-semibold text-gray-800 mb-4">Filter Kategori</h3>
            <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-gray-600 text-sm mb-1">Cari Kategori</label>
                    <input type="text" name="search" placeholder="Nama kategori..." 
                           value="{{ request('search') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1E3A2E] focus:border-[#1E3A2E]">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="bg-[#1E3A2E] hover:bg-[#2E7D32] text-white px-4 py-2 rounded-md w-full transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-search"></i>
                        <span>Cari</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fa-solid fa-circle-check text-green-500 mr-3"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-green-700">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fa-solid fa-circle-exclamation text-red-500 mr-3"></i>
                    <span>{{ session('error') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-red-700">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>
        @endif

        <!-- Tabel Kategori -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="font-semibold text-gray-800">
                    <i class="fa-solid fa-tags mr-2 text-[#1E3A2E]"></i>
                    Daftar Kategori
                </h3>
                <span class="text-sm text-gray-500">
                    Total: {{ $categories->total() }} kategori
                </span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Produk</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dibuat</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($categories as $index => $category)
                        <tr class="hover:bg-gray-50" id="category-{{ $category->id }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $categories->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-800">
                                {{ $category->name }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate">
                                {{ $category->description ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
                                    {{ $category->products_count ?? $category->products()->count() }} produk
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $category->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('admin.categories.edit', $category->id) }}" 
                                   class="text-blue-600 hover:text-blue-800 mr-3" title="Edit">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </a>
                                <button onclick="deleteCategory({{ $category->id }}, '{{ $category->name }}')" 
                                        class="text-red-600 hover:text-red-800" title="Hapus">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                <i class="fa-solid fa-tags text-4xl mb-3 text-gray-300"></i>
                                <p>Belum ada data kategori</p>
                                <a href="{{ route('admin.categories.create') }}" class="text-[#1E3A2E] hover:underline mt-2 inline-block">
                                    Tambah kategori pertama
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200 flex justify-between items-center">
                <p class="text-sm text-gray-500">
                    Menampilkan {{ $categories->firstItem() ?? 0 }} - {{ $categories->lastItem() ?? 0 }} 
                    dari {{ $categories->total() }} kategori
                </p>
                <div class="flex space-x-2">
                    {{ $categories->links() }}
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
        function deleteCategory(id, name) {
            Swal.fire({
                title: 'Hapus Kategori?',
                text: `Yakin ingin menghapus kategori "${name}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/admin/categories/' + id,
                        method: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(response) {
                            if (response.success) {
                                $('#category-' + id).fadeOut();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Terhapus!',
                                    text: response.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: xhr.responseJSON?.message || 'Terjadi kesalahan'
                            });
                        }
                    });
                }
            });
        }
    </script>

</body>
</html>