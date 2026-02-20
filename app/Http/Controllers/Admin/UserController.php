<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
                  ->orWhere('nip', 'like', "%{$request->search}%");
            });
        }

        // Filter by role
        if ($request->role) {
            $query->where('role', $request->role);
        }

        // Filter by status (active/inactive)
        if ($request->has('active') && $request->active !== '') {
            $query->where('is_active', $request->active);
        }

        $users = $query->orderBy('name')->paginate(10);
        
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show form for creating new user.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,ktu,krani',
            'nip' => 'nullable|string|unique:users',
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:100',
        ]);

        try {
            DB::beginTransaction();

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'nip' => $request->nip,
                'phone' => $request->phone,
                'position' => $request->position,
                'is_active' => true,
            ]);

            DB::commit();

            return redirect()->route('admin.users.index')
                ->with('success', 'User berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menambahkan user: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $user->load('transactions');
        
        $stats = [
            'total_transactions' => $user->transactions()->count(),
            'total_masuk' => $user->transactions()->where('type', 'masuk')->count(),
            'total_keluar' => $user->transactions()->where('type', 'keluar')->count(),
            'last_login' => $user->last_login_at,
        ];

        return view('admin.users.show', compact('user', 'stats'));
    }

    /**
     * Show form for editing user.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,ktu,krani',
            'nip' => 'nullable|string|unique:users,nip,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:100',
            'is_active' => 'sometimes|boolean',
        ]);

        try {
            DB::beginTransaction();

            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
                'nip' => $request->nip,
                'phone' => $request->phone,
                'position' => $request->position,
                'is_active' => $request->has('is_active') ? $request->is_active : $user->is_active,
            ];

            // Update password only if provided
            if ($request->filled('password')) {
                $request->validate([
                    'password' => 'string|min:8|confirmed',
                ]);
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);

            DB::commit();

            return redirect()->route('admin.users.index')
                ->with('success', 'User berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui user: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user)
    {
        try {
            DB::beginTransaction();

            // Prevent deleting yourself
            if ($user->id === auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus akun Anda sendiri.'
                ], 400);
            }

            // Check if user has transactions
            if ($user->transactions()->count() > 0) {
                // Soft delete? Or reject?
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak dapat dihapus karena memiliki riwayat transaksi.'
                ], 400);
            }

            $user->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'User berhasil dihapus.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle user active status.
     */
    public function toggleActive(User $user)
    {
        try {
            DB::beginTransaction();

            $user->update([
                'is_active' => !$user->is_active
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Status user berhasil diubah.',
                'is_active' => $user->is_active
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reset user password.
     */
    public function resetPassword(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            DB::beginTransaction();

            $user->update([
                'password' => Hash::make($request->password),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Password berhasil direset.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal mereset password: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export users to Excel/CSV.
     */
    public function exportExcel(Request $request)
    {
        $query = User::query();

        if ($request->role) {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('name')->get();

        $filename = "data-user-" . date('Y-m-d') . ".csv";
        $handle = fopen('php://output', 'w');

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        // Header CSV
        fputcsv($handle, [
            'NIP',
            'Nama',
            'Email',
            'Role',
            'No. Telepon',
            'Jabatan',
            'Status',
            'Terdaftar',
            'Terakhir Login'
        ]);

        // Data
        foreach ($users as $user) {
            fputcsv($handle, [
                $user->nip ?? '-',
                $user->name,
                $user->email,
                $user->role,
                $user->phone ?? '-',
                $user->position ?? '-',
                $user->is_active ? 'Aktif' : 'Nonaktif',
                $user->created_at->format('d/m/Y'),
                $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : '-'
            ]);
        }

        fclose($handle);
        exit;
    }
}