<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Tampilkan halaman login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Proses login
     */
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        // Attempt login
        $credentials = $request->only('email', 'password');
        
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            // Regenerate session untuk keamanan
            $request->session()->regenerate();

            // Redirect ke dashboard
            return redirect()->intended('dashboard');
        }

        // Kalau gagal
        throw ValidationException::withMessages([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ]);
    }

    /**
     * Proses logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}