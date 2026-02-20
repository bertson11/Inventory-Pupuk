<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Users - Asian Agri</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar sederhana -->
        <div class="w-64 bg-[#1E3A2E] text-white p-4">
            <h2 class="text-xl font-bold mb-6">ASIAN AGRI</h2>
            <ul>
                <li class="mb-2"><a href="{{ route('admin.dashboard') }}" class="block p-2 hover:bg-[#2E7D32] rounded">Dashboard</a></li>
                <li class="mb-2"><a href="{{ route('admin.products.index') }}" class="block p-2 hover:bg-[#2E7D32] rounded">Produk</a></li>
                <li class="mb-2"><a href="{{ route('admin.users.index') }}" class="block p-2 bg-[#2E7D32] rounded">Users</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Manajemen Users</h1>
                <a href="{{ route('admin.users.create') }}" class="bg-[#1E3A2E] text-white px-4 py-2 rounded hover:bg-[#2E7D32]">
                    + Tambah User
                </a>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">NIP</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($users as $user)
                        <tr>
                            <td class="px-6 py-4">{{ $user->name }}</td>
                            <td class="px-6 py-4">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded-full 
                                    @if($user->role == 'admin') bg-purple-100 text-purple-800
                                    @elseif($user->role == 'ktu') bg-blue-100 text-blue-800
                                    @else bg-green-100 text-green-800
                                    @endif">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="px-6 py-4">{{ $user->nip ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</body>
</html>