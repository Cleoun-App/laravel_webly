<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function login()
    {
        return view('auth.auth_login');
    }

    public function attempt_login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->route('home')->with('success', 'Login Berhasil');
        } else {
            return redirect()->route('login')->with('error', 'Pastikan username dan password sesuai!!');
        }
    }

    public function logout()
    {
        auth()->logout();

        return redirect()->route('login');
    }
}
