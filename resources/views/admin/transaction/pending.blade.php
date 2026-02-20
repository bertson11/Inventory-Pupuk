<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Approvals - Asian Agri</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="w-64 bg-[#1E3A2E] text-white p-4">
            <h2 class="text-xl font-bold mb-6">ASIAN AGRI</h2>
            <ul>
                <li class="mb-2"><a href="{{ route('admin.dashboard') }}" class="block p-2 hover:bg-[#2E7D32] rounded">Dashboard</a></li>
                <li class="mb-2"><a href="{{ route('admin.transactions.pending') }}" class="block p-2 bg-[#2E7D32] rounded">Pending Approvals</a></li>
                <li class="mb-2"><a href="{{ route('admin.transactions.index') }}" class="block p-2 hover:bg-[#2E7D32] rounded">Semua Transaksi</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <h1 class="text-2xl font-bold mb-6">Pending Approvals</h1>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Waktu</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produk</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($transactions as $trx)
                        <tr id="trx-{{ $trx->id }}">
                            <td class="px-6 py-4">{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4">{{ $trx->product->name }}</td>
                            <td class="px-6 py-4">
                                @if($trx->type == 'masuk')
                                    <span class="text-green-600">Masuk</span>
                                @else
                                    <span class="text-orange-600">Keluar</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">{{ number_format($trx->quantity) }} kg</td>
                            <td class="px-6 py-4">{{ $trx->user->name }}</td>
                            <td class="px-6 py-4">
                                <button onclick="approve({{ $trx->id }})" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded mr-2">
                                    Approve
                                </button>
                                <button onclick="reject({{ $trx->id }})" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">
                                    Reject
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                Tidak ada pending approval
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function approve(id) {
            if (confirm('Yakin ingin menyetujui transaksi ini?')) {
                $.post('/admin/transactions/' + id + '/approve', {
                    _token: '{{ csrf_token() }}'
                }, function(response) {
                    if (response.success) {
                        $('#trx-' + id).fadeOut();
                        alert('Transaksi disetujui');
                    }
                });
            }
        }

        function reject(id) {
            if (confirm('Yakin ingin menolak transaksi ini?')) {
                $.post('/admin/transactions/' + id + '/reject', {
                    _token: '{{ csrf_token() }}'
                }, function(response) {
                    if (response.success) {
                        $('#trx-' + id).fadeOut();
                        alert('Transaksi ditolak');
                    }
                });
            }
        }
    </script>
</body>
</html>