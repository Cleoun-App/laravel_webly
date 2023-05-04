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


    /**
     *  Logiin attemption function
     *
     *  @param \Illuminate\Http\Request $request
     */
    public function attempt_login(Request $request)
    {

        // Data validation
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        // if success redirect to the main page with flash messsage
        if (Auth::attempt($credentials)) {
            return redirect()->route('dashboard.index')->with('success', 'Login Berhasil');
        } else {
            return redirect()->route('login')->with('error', 'Pastikan username dan password sesuai!!');
        }
    }


    /**
     *  Logout function
     *
     *  @return void
     */
    public function logout()
    {
        auth()->logout();

        return redirect()->route('login');
    }
}
