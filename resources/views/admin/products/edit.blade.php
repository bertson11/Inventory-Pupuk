<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - Asian Agri Inventory</title>
    
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
        
        <!-- Welcome Message -->
        <div class="mb-8">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">
                Edit Produk: {{ $product->name }}
            </h1>
            <p class="text-gray-600 mt-1">
                {{ now()->format('l, d F Y') }} | 
                <span class="text-[#1E3A2E] font-semibold">Perbarui informasi produk</span>
            </p>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <a href="{{ route('admin.products.index') }}" class="bg-white hover:shadow-md rounded-lg p-4 text-center transition border border-gray-200">
                <i class="fa-solid fa-arrow-left text-gray-600 text-2xl mb-2"></i>
                <p class="text-sm font-medium text-gray-700">Kembali</p>
            </a>
            <a href="{{ route('admin.products.create') }}" class="bg-white hover:shadow-md rounded-lg p-4 text-center transition border border-gray-200">
                <i class="fa-solid fa-plus-circle text-green-600 text-2xl mb-2"></i>
                <p class="text-sm font-medium text-gray-700">Tambah Baru</p>
            </a>
            <a href="#" onclick="confirmDelete()" class="bg-white hover:shadow-md rounded-lg p-4 text-center transition border border-gray-200">
                <i class="fa-solid fa-trash-can text-red-600 text-2xl mb-2"></i>
                <p class="text-sm font-medium text-gray-700">Hapus</p>
            </a>
            <a href="{{ route('admin.dashboard') }}" class="bg-white hover:shadow-md rounded-lg p-4 text-center transition border border-gray-200">
                <i class="fa-solid fa-home text-[#1E3A2E] text-2xl mb-2"></i>
                <p class="text-sm font-medium text-gray-700">Dashboard</p>
            </a>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="font-semibold text-gray-800">
                    <i class="fa-solid fa-pen-to-square text-[#1E3A2E] mr-2"></i>
                    Edit Produk: {{ $product->code }}
                </h3>
            </div>
            
            <div class="p-6">
                <form action="{{ route('admin.products.update', $product->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Kode Produk -->
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                Kode Produk <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="code" 
                                   value="{{ old('code', $product->code) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1E3A2E] focus:border-[#1E3A2E] @error('code') border-red-500 @enderror"
                                   required>
                            @error('code')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nama Produk -->
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                Nama Produk <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   value="{{ old('name', $product->name) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1E3A2E] focus:border-[#1E3A2E] @error('name') border-red-500 @enderror"
                                   required>
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kategori -->
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                Kategori
                            </label>
                            <select name="category_id" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1E3A2E] focus:border-[#1E3A2E]">
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                        {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Satuan -->
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                Satuan <span class="text-red-500">*</span>
                            </label>
                            <select name="unit" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1E3A2E] focus:border-[#1E3A2E]"
                                    required>
                                <option value="kg" {{ old('unit', $product->unit) == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                                <option value="liter" {{ old('unit', $product->unit) == 'liter' ? 'selected' : '' }}>Liter</option>
                                <option value="sak" {{ old('unit', $product->unit) == 'sak' ? 'selected' : '' }}>Sak</option>
                                <option value="karung" {{ old('unit', $product->unit) == 'karung' ? 'selected' : '' }}>Karung</option>
                                <option value="ton" {{ old('unit', $product->unit) == 'ton' ? 'selected' : '' }}>Ton</option>
                            </select>
                        </div>

                        <!-- Harga -->
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                Harga (Rp)
                            </label>
                            <input type="number" 
                                   name="price" 
                                   value="{{ old('price', $product->price) }}"
                                   min="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1E3A2E] focus:border-[#1E3A2E]">
                        </div>

                        <!-- Minimum Stok -->
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                Minimum Stok
                            </label>
                            <input type="number" 
                                   name="min_stock" 
                                   value="{{ old('min_stock', $product->min_stock) }}"
                                   min="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1E3A2E] focus:border-[#1E3A2E]">
                            <p class="text-gray-500 text-xs mt-1">Batas stok menipis</p>
                        </div>

                        <!-- Stok Saat Ini (Read Only) -->
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                Stok Saat Ini
                            </label>
                            <div class="flex items-center">
                                <input type="text" 
                                       value="{{ number_format($product->stock) }} {{ $product->unit }}"
                                       class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md"
                                       readonly>
                                <span class="ml-2 px-2 py-1 text-xs rounded-full 
                                    @if($product->stock_status == 'Aman') bg-green-100 text-green-800
                                    @elseif($product->stock_status == 'Menipis') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ $product->stock_status }}
                                </span>
                            </div>
                        </div>

                        <!-- Deskripsi (Full width) -->
                        <div class="md:col-span-2">
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                Deskripsi
                            </label>
                            <textarea name="description" 
                                      rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1E3A2E] focus:border-[#1E3A2E]">{{ old('description', $product->description) }}</textarea>
                        </div>
                    </div>

                    <!-- Tombol Submit -->
                    <div class="flex justify-end gap-3 mt-6">
                        <a href="{{ route('admin.products.index') }}" 
                           class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-lg transition">
                            Batal
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-[#1E3A2E] hover:bg-[#2E7D32] text-white rounded-lg transition flex items-center gap-2">
                            <i class="fa-solid fa-save"></i>
                            <span>Update Produk</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Hidden form untuk delete -->
    <form id="delete-form" action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <!-- Footer -->
    <footer class="bg-white border-t mt-8 py-4">
        <div class="container mx-auto px-4 text-center text-sm text-gray-500">
            <p>&copy; {{ date('Y') }} Asian Agri. All rights reserved.</p>
            <p class="text-xs mt-1">Inventory System v1.0.0</p>
        </div>
    </footer>

    <script>
        function confirmDelete() {
            Swal.fire({
                title: 'Hapus Produk?',
                text: 'Apakah Anda yakin ingin menghapus produk ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form').submit();
                }
            });
        }
    </script>

</body>
</html>