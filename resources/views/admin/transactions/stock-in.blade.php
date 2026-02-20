<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stok Masuk - Asian Agri Inventory</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Font Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- jQuery & Select2 -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        .select2-container .select2-selection--single {
            height: 42px;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 40px;
            padding-left: 12px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px;
        }
        .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
            background-color: #1E3A2E;
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
                Form Stok Masuk
            </h1>
            <p class="text-gray-600 mt-1">
                {{ now()->format('l, d F Y') }} | 
                <span class="text-[#1E3A2E] font-semibold">Catat penerimaan stok pupuk dari supplier</span>
            </p>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
            <a href="{{ route('admin.transactions.index') }}" class="bg-white hover:shadow-md rounded-lg p-4 text-center transition border border-gray-200">
                <i class="fa-solid fa-clock-rotate-left text-gray-600 text-2xl mb-2"></i>
                <p class="text-sm font-medium text-gray-700">Semua Transaksi</p>
            </a>
            <a href="{{ route('admin.transactions.stock-in.create') }}" class="bg-white hover:shadow-md rounded-lg p-4 text-center transition border border-gray-200 border-l-4 border-green-500">
                <i class="fa-solid fa-arrow-down text-green-600 text-2xl mb-2"></i>
                <p class="text-sm font-medium text-gray-700">Stok Masuk</p>
            </a>
            <a href="{{ route('admin.transactions.stock-out.create') }}" class="bg-white hover:shadow-md rounded-lg p-4 text-center transition border border-gray-200">
                <i class="fa-solid fa-arrow-up text-orange-600 text-2xl mb-2"></i>
                <p class="text-sm font-medium text-gray-700">Stok Keluar</p>
            </a>
            <a href="{{ route('admin.products.index') }}" class="bg-white hover:shadow-md rounded-lg p-4 text-center transition border border-gray-200">
                <i class="fa-solid fa-boxes text-blue-600 text-2xl mb-2"></i>
                <p class="text-sm font-medium text-gray-700">Produk</p>
            </a>
            <a href="{{ route('admin.dashboard') }}" class="bg-white hover:shadow-md rounded-lg p-4 text-center transition border border-gray-200">
                <i class="fa-solid fa-home text-[#1E3A2E] text-2xl mb-2"></i>
                <p class="text-sm font-medium text-gray-700">Dashboard</p>
            </a>
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

        @if($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                <div class="flex items-start">
                    <i class="fa-solid fa-circle-exclamation text-red-500 mr-3 mt-0.5"></i>
                    <div>
                        <p class="font-medium">Terjadi kesalahan:</p>
                        <ul class="list-disc list-inside text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Form Card -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-green-50">
                <h3 class="font-semibold text-gray-800">
                    <i class="fa-solid fa-arrow-down text-green-600 mr-2"></i>
                    Form Input Stok Masuk
                </h3>
                <p class="text-xs text-gray-500 mt-1">Isi form dengan lengkap. Transaksi akan membutuhkan approval dari KTU.</p>
            </div>
            
            <div class="p-6">
                <form action="{{ route('admin.transactions.stock-in.store') }}" method="POST" id="stockInForm">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Pilih Produk -->
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                Pilih Produk <span class="text-red-500">*</span>
                            </label>
                            <select name="product_id" id="product_id" class="w-full" required>
                                <option value="">-- Pilih Produk --</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" 
                                        data-stock="{{ $product->stock }}"
                                        data-unit="{{ $product->unit }}"
                                        data-min="{{ $product->min_stock }}"
                                        data-price="{{ $product->price }}"
                                        {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                        [{{ $product->code }}] {{ $product->name }} 
                                        (Stok: {{ $product->stock }} {{ $product->unit }})
                                    </option>
                                @endforeach
                            </select>
                            @error('product_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            
                            <!-- Info Produk Terpilih -->
                            <div id="productInfo" class="mt-2 p-2 bg-blue-50 rounded-md hidden">
                                <p class="text-xs text-blue-700">
                                    <span id="currentStock"></span>
                                    <br>
                                    <span id="minStock"></span>
                                    <br>
                                    <span id="productPrice"></span>
                                </p>
                            </div>
                        </div>

                        <!-- Pilih Supplier -->
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                Supplier <span class="text-red-500">*</span>
                            </label>
                            <select name="supplier_id" id="supplier_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1E3A2E]" required>
                                <option value="">-- Pilih Supplier --</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('supplier_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jumlah -->
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                Jumlah <span class="text-red-500">*</span>
                            </label>
                            <div class="flex gap-2">
                                <input type="number" 
                                       name="quantity" 
                                       id="quantity"
                                       value="{{ old('quantity') }}"
                                       min="1"
                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1E3A2E]"
                                       required>
                                <span id="unit_display" class="inline-flex items-center px-3 bg-gray-100 border border-gray-300 rounded-md text-gray-600">
                                    kg
                                </span>
                            </div>
                            @error('quantity')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal -->
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                Tanggal <span class="text-red-500">*</span>
                            </label>
                            <input type="date" 
                                   name="date" 
                                   id="date"
                                   value="{{ old('date', date('Y-m-d')) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1E3A2E]"
                                   required>
                            @error('date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- No. Invoice / Referensi -->
                        <div class="md:col-span-2">
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                No. Invoice / Referensi
                            </label>
                            <input type="text" 
                                   name="reference" 
                                   value="{{ old('reference') }}"
                                   placeholder="Contoh: INV/2024/001"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1E3A2E]">
                            @error('reference')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Keterangan -->
                        <div class="md:col-span-2">
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                Keterangan
                            </label>
                            <textarea name="notes" 
                                      rows="3"
                                      placeholder="Catatan tambahan (misal: kondisi barang, pengiriman, dll)"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1E3A2E]">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Preview Ringkasan -->
                    <div class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <h4 class="font-semibold text-gray-700 mb-3">Ringkasan</h4>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div>
                                <p class="text-xs text-gray-500">Produk</p>
                                <p class="font-medium text-gray-800" id="previewProduct">-</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Supplier</p>
                                <p class="font-medium text-gray-800" id="previewSupplier">-</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Jumlah</p>
                                <p class="font-medium text-gray-800" id="previewQuantity">-</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Tanggal</p>
                                <p class="font-medium text-gray-800" id="previewDate">{{ date('d/m/Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Info Approval -->
                    <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mt-6 rounded">
                        <div class="flex items-start">
                            <i class="fa-solid fa-clock text-yellow-500 mt-0.5 mr-3"></i>
                            <div>
                                <p class="text-sm text-yellow-700 font-medium">Perlu Approval KTU</p>
                                <p class="text-xs text-yellow-600 mt-1">
                                    Stok akan langsung bertambah, namun transaksi akan masuk ke daftar pending approval.
                                    KTU akan melakukan verifikasi dan menyetujui/menolak transaksi ini.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Submit -->
                    <div class="flex justify-end gap-3 mt-6">
                        <a href="{{ route('admin.transactions.index') }}" 
                           class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-lg transition">
                            Batal
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-[#1E3A2E] hover:bg-[#2E7D32] text-white rounded-lg transition flex items-center gap-2">
                            <i class="fa-solid fa-save"></i>
                            <span>Simpan Stok Masuk</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Recent Stock In Transactions -->
        <div class="mt-8 bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="font-semibold text-gray-800">
                    <i class="fa-solid fa-clock-rotate-left mr-2 text-gray-600"></i>
                    10 Transaksi Stok Masuk Terakhir
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Tanggal</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Produk</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Supplier</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Jumlah</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @php
                            $recentStokMasuk = \App\Models\Transaction::with(['product', 'supplier'])
                                ->where('type', 'masuk')
                                ->latest()
                                ->limit(10)
                                ->get();
                        @endphp
                        @forelse($recentStokMasuk as $trx)
                        <tr>
                            <td class="px-4 py-2 text-sm">{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-2 text-sm">{{ $trx->product->name }}</td>
                            <td class="px-4 py-2 text-sm">{{ $trx->supplier->name ?? '-' }}</td>
                            <td class="px-4 py-2 text-sm">{{ number_format($trx->quantity) }} {{ $trx->product->unit }}</td>
                            <td class="px-4 py-2">
                                <span class="px-2 py-1 text-xs rounded-full 
                                    @if($trx->status == 'approved') bg-green-100 text-green-800
                                    @elseif($trx->status == 'pending') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ $trx->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-4 text-center text-gray-500">
                                Belum ada transaksi stok masuk
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
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
            // Initialize Select2
            $('#product_id').select2({
                placeholder: '-- Pilih Produk --',
                allowClear: true,
                width: '100%'
            });

            $('#supplier_id').select2({
                placeholder: '-- Pilih Supplier --',
                allowClear: true,
                width: '100%'
            });

            // Show product info when product selected
            $('#product_id').on('change', function() {
                const selected = $(this).find('option:selected');
                const stock = selected.data('stock');
                const unit = selected.data('unit');
                const minStock = selected.data('min');
                const price = selected.data('price');

                if (selected.val()) {
                    $('#productInfo').removeClass('hidden');
                    $('#currentStock').html(`Stok saat ini: <strong>${stock} ${unit}</strong>`);
                    $('#minStock').html(`Min stok: <strong>${minStock} ${unit}</strong>`);
                    $('#productPrice').html(`Harga: <strong>Rp ${new Intl.NumberFormat('id-ID').format(price)}</strong>`);
                    $('#unit_display').text(unit);
                    
                    // Update preview
                    $('#previewProduct').text(selected.text().split(']')[1] || selected.text());
                } else {
                    $('#productInfo').addClass('hidden');
                    $('#unit_display').text('kg');
                    $('#previewProduct').text('-');
                }
            });

            // Update preview on input change
            $('#quantity').on('input', function() {
                const qty = $(this).val();
                const unit = $('#unit_display').text();
                if (qty) {
                    $('#previewQuantity').text(`${qty} ${unit}`);
                } else {
                    $('#previewQuantity').text('-');
                }
            });

            $('#supplier_id').on('change', function() {
                const selected = $(this).find('option:selected');
                $('#previewSupplier').text(selected.text() || '-');
            });

            $('#date').on('change', function() {
                const date = $(this).val();
                if (date) {
                    const parts = date.split('-');
                    $('#previewDate').text(`${parts[2]}/${parts[1]}/${parts[0]}`);
                }
            });

            // Trigger initial preview if there are old values
            @if(old('product_id'))
                $('#product_id').trigger('change');
            @endif
            @if(old('quantity'))
                $('#quantity').trigger('input');
            @endif
            @if(old('supplier_id'))
                $('#supplier_id').trigger('change');
            @endif
        });
    </script>

</body>
</html>