<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Produk - Asian Agri Inventory</title>
    
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

    <!-- Navbar SAMA PERSIS dengan dashboard -->
    <nav class="bg-[#1E3A2E] text-white shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center space-x-2">
                    <i class="fa-solid fa-seedling text-xl"></i>
                    <span class="font-bold text-lg">ASIAN AGRI - ADMIN</span>
                </div>
                
                <!-- User Menu -->
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
        
        <!-- Welcome Message (SAMA dengan dashboard) -->
        <div class="mb-8">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">
                Manajemen Produk
            </h1>
            <p class="text-gray-600 mt-1">
                {{ now()->format('l, d F Y') }} | 
                <span class="text-[#1E3A2E] font-semibold">Kelola data produk pupuk</span>
            </p>
        </div>

        <!-- Quick Actions (SAMA STYLE dengan dashboard) -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
            <a href="{{ route('admin.products.index') }}" class="bg-white hover:shadow-md rounded-lg p-4 text-center transition border border-gray-200 border-l-4 border-[#1E3A2E]">
                <i class="fa-solid fa-boxes text-[#1E3A2E] text-2xl mb-2"></i>
                <p class="text-sm font-medium text-gray-700">Semua Produk</p>
            </a>
            <a href="{{ route('admin.products.create') }}" class="bg-white hover:shadow-md rounded-lg p-4 text-center transition border border-gray-200">
                <i class="fa-solid fa-plus-circle text-green-600 text-2xl mb-2"></i>
                <p class="text-sm font-medium text-gray-700">Tambah Produk</p>
            </a>
            <a href="{{ route('admin.categories.index') }}" class="bg-white hover:shadow-md rounded-lg p-4 text-center transition border border-gray-200">
                <i class="fa-solid fa-tags text-blue-600 text-2xl mb-2"></i>
                <p class="text-sm font-medium text-gray-700">Kategori</p>
            </a>
            <a href="{{ route('admin.suppliers.index') }}" class="bg-white hover:shadow-md rounded-lg p-4 text-center transition border border-gray-200">
                <i class="fa-solid fa-truck text-orange-600 text-2xl mb-2"></i>
                <p class="text-sm font-medium text-gray-700">Supplier</p>
            </a>
            <a href="{{ route('admin.dashboard') }}" class="bg-white hover:shadow-md rounded-lg p-4 text-center transition border border-gray-200">
                <i class="fa-solid fa-arrow-left text-gray-600 text-2xl mb-2"></i>
                <p class="text-sm font-medium text-gray-700">Kembali</p>
            </a>
        </div>

        <!-- Search & Filter Section (Card style SAMA dengan dashboard) -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h3 class="font-semibold text-gray-800 mb-4">Filter Produk</h3>
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-gray-600 text-sm mb-1">Cari</label>
                    <input type="text" name="search" placeholder="Nama atau kode..." 
                           value="{{ request('search') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1E3A2E] focus:border-[#1E3A2E]">
                </div>
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
                        <option value="">Semua Status</option>
                        <option value="aman" {{ request('status') == 'aman' ? 'selected' : '' }}>Aman</option>
                        <option value="menipis" {{ request('status') == 'menipis' ? 'selected' : '' }}>Menipis</option>
                        <option value="kritis" {{ request('status') == 'kritis' ? 'selected' : '' }}>Kritis</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="bg-[#1E3A2E] hover:bg-[#2E7D32] text-white px-4 py-2 rounded-md w-full transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-search"></i>
                        <span>Filter</span>
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

        <!-- Tabel Produk (SAMA STYLE dengan tabel di dashboard) -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="font-semibold text-gray-800">
                    <i class="fa-solid fa-boxes mr-2 text-[#1E3A2E]"></i>
                    Daftar Produk Pupuk
                </h3>
                <span class="text-sm text-gray-500">
                    Total: {{ $products->total() }} item
                </span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Produk</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Min Stok</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($products as $product)
                        <tr class="hover:bg-gray-50" id="product-{{ $product->id }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $product->code }}</td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-800">{{ $product->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $product->category->name ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-semibold">{{ number_format($product->stock) }}</span>
                                <span class="text-xs text-gray-500">{{ $product->unit }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $product->min_stock }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'Aman' => 'bg-green-100 text-green-800',
                                        'Menipis' => 'bg-yellow-100 text-yellow-800',
                                        'Kritis' => 'bg-red-100 text-red-800'
                                    ];
                                    $colorClass = $statusColors[$product->stock_status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="px-2 py-1 text-xs rounded-full {{ $colorClass }}">
                                    {{ $product->stock_status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('admin.products.edit', $product->id) }}" 
                                   class="text-blue-600 hover:text-blue-800 mr-3" title="Edit">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </a>
                                <button onclick="updateStock({{ $product->id }}, '{{ $product->name }}')" 
                                        class="text-green-600 hover:text-green-800 mr-3" title="Update Stok">
                                    <i class="fa-solid fa-scale-balanced"></i>
                                </button>
                                <button onclick="deleteProduct({{ $product->id }}, '{{ $product->name }}')" 
                                        class="text-red-600 hover:text-red-800" title="Hapus">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                                <i class="fa-solid fa-box-open text-4xl mb-3 text-gray-300"></i>
                                <p>Belum ada data produk</p>
                                <a href="{{ route('admin.products.create') }}" class="text-[#1E3A2E] hover:underline mt-2 inline-block">
                                    Tambah produk pertama
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination (SAMA dengan dashboard) -->
            <div class="px-6 py-4 border-t border-gray-200 flex justify-between items-center">
                <p class="text-sm text-gray-500">
                    Menampilkan {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} 
                    dari {{ $products->total() }} item
                </p>
                <div class="flex space-x-2">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Update Stok -->
    <div id="stockModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-8 max-w-md w-full">
            <h3 class="text-xl font-bold mb-4" id="stockModalTitle">Update Stok</h3>
            <form id="stockForm">
                @csrf
                <input type="hidden" id="stock_product_id">
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Tipe</label>
                    <div class="flex gap-4">
                        <label class="flex items-center">
                            <input type="radio" name="stock_type" value="add" checked class="mr-2">
                            <span class="text-green-600"><i class="fa-solid fa-plus mr-1"></i>Tambah</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="stock_type" value="subtract" class="mr-2">
                            <span class="text-orange-600"><i class="fa-solid fa-minus mr-1"></i>Kurangi</span>
                        </label>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Jumlah</label>
                    <input type="number" id="stock_quantity" name="quantity" min="1" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#1E3A2E]"
                           required>
                </div>
                
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeStockModal()" 
                            class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-lg transition">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-[#1E3A2E] hover:bg-[#2E7D32] text-white rounded-lg transition">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        function updateStock(id, name) {
            $('#stock_product_id').val(id);
            $('#stockModalTitle').text('Update Stok - ' + name);
            $('#stockModal').removeClass('hidden').addClass('flex');
        }

        function closeStockModal() {
            $('#stockModal').removeClass('flex').addClass('hidden');
            $('#stockForm')[0].reset();
        }

        $('#stockForm').on('submit', function(e) {
            e.preventDefault();
            
            const id = $('#stock_product_id').val();
            const type = $('input[name="stock_type"]:checked').val();
            const quantity = $('#stock_quantity').val();

            $.ajax({
                url: '/admin/products/' + id + '/update-stock',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    type: type,
                    quantity: quantity
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        });
                        closeStockModal();
                        setTimeout(() => location.reload(), 1500);
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: xhr.responseJSON?.message || 'Terjadi kesalahan'
                    });
                }
            });
        });

        function deleteProduct(id, name) {
            Swal.fire({
                title: 'Hapus Produk?',
                text: `Yakin ingin menghapus ${name}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/admin/products/' + id,
                        method: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(response) {
                            if (response.success) {
                                $('#product-' + id).fadeOut();
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

        // Close modal when clicking outside
        $(window).on('click', function(e) {
            if ($(e.target).is('#stockModal')) {
                closeStockModal();
            }
        });
    </script>

</body>
</html>