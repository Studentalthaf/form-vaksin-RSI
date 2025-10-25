<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        
        return view('auth.login');
    }

    /**
     * Proses login dengan validasi dan rate limiting
     */
    public function login(Request $request)
    {
        // Rate limiting - max 5 percobaan per menit
        $key = 'login.' . $request->ip();
        
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            
            throw ValidationException::withMessages([
                'email' => ['Terlalu banyak percobaan login. Silakan coba lagi dalam ' . $seconds . ' detik.'],
            ]);
        }

        // Validasi input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:6'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
        ]);

        // Coba autentikasi
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // Login berhasil - clear rate limiter
            RateLimiter::clear($key);
            
            // Regenerate session untuk keamanan
            $request->session()->regenerate();
            
            return redirect()->intended(route('dashboard'))->with('success', 'Selamat datang, ' . Auth::user()->nama . '!');
        }

        // Login gagal - increment rate limiter
        RateLimiter::hit($key, 60);

        // Kembalikan error
        throw ValidationException::withMessages([
            'email' => ['Email atau password salah.'],
        ]);
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')->with('success', 'Anda telah logout.');
    }
}
