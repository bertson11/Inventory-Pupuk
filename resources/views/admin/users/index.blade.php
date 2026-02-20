<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen User - Asian Agri Inventory</title>
    
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
                Manajemen User
            </h1>
            <p class="text-gray-600 mt-1">
                {{ now()->format('l, d F Y') }} | 
                <span class="text-[#1E3A2E] font-semibold">Kelola pengguna sistem</span>
            </p>
        </div>

        <!-- Quick Actions (SAMA STYLE dengan dashboard) -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
            <a href="{{ route('admin.users.index') }}" class="bg-white hover:shadow-md rounded-lg p-4 text-center transition border border-gray-200 border-l-4 border-[#1E3A2E]">
                <i class="fa-solid fa-users text-[#1E3A2E] text-2xl mb-2"></i>
                <p class="text-sm font-medium text-gray-700">Semua User</p>
            </a>
            <a href="{{ route('admin.users.create') }}" class="bg-white hover:shadow-md rounded-lg p-4 text-center transition border border-gray-200">
                <i class="fa-solid fa-user-plus text-green-600 text-2xl mb-2"></i>
                <p class="text-sm font-medium text-gray-700">Tambah User</p>
            </a>
            <a href="{{ route('admin.users.index') }}?export=excel" class="bg-white hover:shadow-md rounded-lg p-4 text-center transition border border-gray-200">
                <i class="fa-solid fa-file-excel text-green-600 text-2xl mb-2"></i>
                <p class="text-sm font-medium text-gray-700">Export Excel</p>
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

        <!-- Filter Section -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h3 class="font-semibold text-gray-800 mb-4">Filter User</h3>
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-gray-600 text-sm mb-1">Cari</label>
                    <input type="text" name="search" placeholder="Nama, email, NIP..." 
                           value="{{ request('search') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1E3A2E] focus:border-[#1E3A2E]">
                </div>
                <div>
                    <label class="block text-gray-600 text-sm mb-1">Role</label>
                    <select name="role" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1E3A2E]">
                        <option value="">Semua Role</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="ktu" {{ request('role') == 'ktu' ? 'selected' : '' }}>KTU</option>
                        <option value="krani" {{ request('role') == 'krani' ? 'selected' : '' }}>Krani</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-600 text-sm mb-1">Status</label>
                    <select name="active" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1E3A2E]">
                        <option value="">Semua</option>
                        <option value="1" {{ request('active') === '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ request('active') === '0' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="bg-[#1E3A2E] hover:bg-[#2E7D32] text-white px-4 py-2 rounded-md w-full">
                        <i class="fa-solid fa-filter mr-2"></i>Filter
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md">
                        Reset
                    </a>
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

        <!-- Tabel Users -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="font-semibold text-gray-800">
                    <i class="fa-solid fa-users mr-2 text-[#1E3A2E]"></i>
                    Daftar Pengguna
                </h3>
                <span class="text-sm text-gray-500">
                    Total: {{ $users->total() }} user
                </span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIP</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Telepon</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jabatan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Terakhir Login</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($users as $index => $user)
                        <tr class="hover:bg-gray-50" id="user-{{ $user->id }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $users->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $user->nip ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-800">
                                {{ $user->name }}
                                @if($user->id === Auth::id())
                                    <span class="ml-2 text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full">Anda</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $user->email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $roleBadges = [
                                        'admin' => 'bg-purple-100 text-purple-800',
                                        'ktu' => 'bg-blue-100 text-blue-800',
                                        'krani' => 'bg-green-100 text-green-800'
                                    ];
                                    $roleClass = $roleBadges[$user->role] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="px-2 py-1 text-xs rounded-full {{ $roleClass }}">
                                    <i class="fa-solid 
                                        @if($user->role == 'admin') fa-crown
                                        @elseif($user->role == 'ktu') fa-check-double
                                        @else fa-warehouse
                                        @endif mr-1">
                                    </i>
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $user->phone ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $user->position ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->is_active)
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                        <i class="fa-solid fa-circle mr-1 text-xs"></i>Aktif
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">
                                        <i class="fa-solid fa-circle mr-1 text-xs"></i>Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                @if($user->last_login_at)
                                    {{ $user->last_login_at->format('d/m/Y H:i') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('admin.users.show', $user->id) }}" 
                                   class="text-blue-600 hover:text-blue-800 mr-2" title="Detail">
                                    <i class="fa-regular fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.users.edit', $user->id) }}" 
                                   class="text-green-600 hover:text-green-800 mr-2" title="Edit">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </a>
                                @if($user->id !== Auth::id())
                                    <button onclick="toggleActive({{ $user->id }}, '{{ $user->name }}', {{ $user->is_active ? 'true' : 'false' }})" 
                                            class="text-yellow-600 hover:text-yellow-800 mr-2" 
                                            title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                        <i class="fa-solid fa-power-off"></i>
                                    </button>
                                    <button onclick="resetPassword({{ $user->id }}, '{{ $user->name }}')" 
                                            class="text-purple-600 hover:text-purple-800 mr-2" title="Reset Password">
                                        <i class="fa-solid fa-key"></i>
                                    </button>
                                    <button onclick="deleteUser({{ $user->id }}, '{{ $user->name }}')" 
                                            class="text-red-600 hover:text-red-800" title="Hapus">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="px-6 py-10 text-center text-gray-500">
                                <i class="fa-solid fa-users text-4xl mb-3 text-gray-300"></i>
                                <p>Belum ada data user</p>
                                <a href="{{ route('admin.users.create') }}" class="text-[#1E3A2E] hover:underline mt-2 inline-block">
                                    Tambah user pertama
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
                    Menampilkan {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} 
                    dari {{ $users->total() }} user
                </p>
                <div class="flex space-x-2">
                    {{ $users->withQueryString()->links() }}
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
        function toggleActive(id, name, isActive) {
            const action = isActive ? 'nonaktifkan' : 'aktifkan';
            Swal.fire({
                title: 'Konfirmasi',
                text: `Yakin ingin ${action} user ${name}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#1E3A2E',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Ya, ' + action + '!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/admin/users/' + id + '/toggle-active',
                        method: 'POST',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message,
                                    timer: 1500
                                }).then(() => {
                                    location.reload();
                                });
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
                }
            });
        }

        function resetPassword(id, name) {
            Swal.fire({
                title: 'Reset Password',
                html: `<p class="mb-3">Reset password untuk <strong>${name}</strong></p>
                       <input type="password" id="password" class="swal2-input" placeholder="Password baru">
                       <input type="password" id="password_confirmation" class="swal2-input" placeholder="Konfirmasi password">`,
                showCancelButton: true,
                confirmButtonColor: '#1E3A2E',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Reset Password',
                cancelButtonText: 'Batal',
                preConfirm: () => {
                    const password = document.getElementById('password').value;
                    const confirm = document.getElementById('password_confirmation').value;
                    
                    if (!password || !confirm) {
                        Swal.showValidationMessage('Password harus diisi');
                        return false;
                    }
                    
                    if (password !== confirm) {
                        Swal.showValidationMessage('Password tidak cocok');
                        return false;
                    }
                    
                    if (password.length < 8) {
                        Swal.showValidationMessage('Password minimal 8 karakter');
                        return false;
                    }
                    
                    return { password, password_confirmation: confirm };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/admin/users/' + id + '/reset-password',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            password: result.value.password,
                            password_confirmation: result.value.password_confirmation
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message,
                                    timer: 1500
                                });
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
                }
            });
        }

        function deleteUser(id, name) {
            Swal.fire({
                title: 'Hapus User?',
                text: `Yakin ingin menghapus user ${name}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/admin/users/' + id,
                        method: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(response) {
                            if (response.success) {
                                $('#user-' + id).fadeOut();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Terhapus!',
                                    text: response.message,
                                    timer: 1500
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