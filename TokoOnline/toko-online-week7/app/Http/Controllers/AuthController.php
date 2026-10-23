<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email',
            'username' => 'required|string|max:50|unique:users,username',
            'password' => 'required|string|min:8|confirmed',
            'no_hp' => 'required|string|max:15',
            'alamat' => 'required|string',
        ]);

        $user = User::create([
            'id_user' => 'USR' . Str::upper(Str::random(8)),
            'nama_lengkap' => $validated['nama_lengkap'],
            'email' => $validated['email'],
            'username' => $validated['username'],
            'password' => $validated['password'],
            'no_hp' => $validated['no_hp'],
            'alamat' => $validated['alamat'],
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('products.index')
            ->with('success', 'Registrasi berhasil.');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended(route('products.index'));
        }

        return back()->withErrors([
            'username' => 'Username atau password tidak sesuai.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('products.index')
            ->with('success', 'Kamu berhasil logout.');
    }
}
