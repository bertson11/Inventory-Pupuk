<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail User - Asian Agri Inventory</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Font Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50">

    <!-- Navbar (sama seperti sebelumnya) -->
    <nav class="bg-[#1E3A2E] text-white shadow-lg">
        <!-- ... copy navbar dari file index ... -->
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        
        <div class="mb-8">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">
                Detail User: {{ $user->name }}
            </h1>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Profile Card -->
            <div class="md:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="w-24 h-24 bg-[#1E3A2E] rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-3xl text-white font-bold">
                            {{ substr($user->name, 0, 1) }}
                        </span>
                    </div>
                    <h2 class="text-xl font-bold">{{ $user->name }}</h2>
                    <p class="text-gray-600">{{ $user->email }}</p>
                    <div class="mt-4">
                        {!! $user->role_badge !!}
                        {!! $user->status_badge !!}
                    </div>
                </div>
            </div>

            <!-- Detail Info -->
            <div class="md:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="font-semibold text-gray-800 mb-4">Informasi Personal</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-gray-500 text-sm">NIP</p>
                            <p class="font-medium">{{ $user->nip ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm">No. Telepon</p>
                            <p class="font-medium">{{ $user->phone ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm">Jabatan</p>
                            <p class="font-medium">{{ $user->position ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm">Terdaftar</p>
                            <p class="font-medium">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Stats Card -->
                <div class="bg-white rounded-lg shadow-md p-6 mt-6">
                    <h3 class="font-semibold text-gray-800 mb-4">Statistik Transaksi</h3>
                    <div class="grid grid-cols-3 gap-4 text-center">
                        <div>
                            <p class="text-2xl font-bold text-[#1E3A2E]">{{ $stats['total_transactions'] }}</p>
                            <p class="text-gray-500 text-sm">Total Transaksi</p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-green-600">{{ $stats['total_masuk'] }}</p>
                            <p class="text-gray-500 text-sm">Stok Masuk</p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-orange-600">{{ $stats['total_keluar'] }}</p>
                            <p class="text-gray-500 text-sm">Stok Keluar</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-300 rounded-lg">Kembali</a>
            <a href="{{ route('admin.users.edit', $user->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Edit</a>
        </div>
    </div>

</body>
</html>