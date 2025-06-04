<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === 'pengurus') {
                return redirect()->route('pengurus.dashboard');
            } elseif ($user->role === 'admin') {
                // Asumsi admin juga punya dashboard sendiri, misalnya 'admin.dashboard'
                // Jika admin juga pakai halaman manajemen artikel sebagai dashboard:
                return redirect()->route('admin.index');
            }
            // Mahasiswa atau role lain defaultnya ke home
            return redirect()->route('home');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $user = Auth::user(); // Mendapatkan informasi pengguna yang login

            // Logika pengalihan setelah login berhasil berdasarkan peran
             if ($user->role === 'pengurus') {
            // dd('Mengarah ke pengurus.dashboard untuk user: ' . $user->name); // <--- TAMBAHKAN UNTUK DEBUG
            return redirect()->intended(route('pengurus.dashboard'))
                             ->with('success', 'Anda telah berhasil login sebagai Pengurus!');
        } elseif ($user->role === 'admin') {
            // dd('Mengarah ke admin.index untuk user: ' . $user->name); // <--- TAMBAHKAN UNTUK DEBUG
            return redirect()->intended(route('admin.index'))
                             ->with('success', 'Anda telah berhasil login sebagai Admin!');
        }
        // Default untuk mahasiswa atau peran lain
        // dd('Mengarah ke home untuk user: ' . $user->name); // <--- TAMBAHKAN UNTUK DEBUG
        return redirect()->intended(route('home'))
                         ->with('success', $remember
                            ? 'Anda telah berhasil login dengan Remember Me aktif!'
                            : 'Anda telah berhasil login!');
    }

    return back()->withErrors([
        'email' => 'Email atau password salah!',
    ])->withInput($request->only('email', 'remember'));
}
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'Anda telah berhasil logout!');
    }
}