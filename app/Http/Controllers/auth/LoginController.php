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
    $request->validate([
        'email' => 'required|string',
        'password' => 'required|string',
    ]);

    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials, $request->filled('remember'))) {

        $request->session()->regenerate();

        // Cek apakah admin
        if (Auth::user()->role !== 'admin') {
            Auth::logout();
            return back()->withErrors([
                'email' => 'Akses hanya untuk admin.',
            ]);
        }

        return redirect()->intended(route('admin.dashboard'));
    }   

    throw \Illuminate\Validation\ValidationException::withMessages([
        'email' => 'Email atau password yang Anda masukkan salah.',
    ]);
    }
}