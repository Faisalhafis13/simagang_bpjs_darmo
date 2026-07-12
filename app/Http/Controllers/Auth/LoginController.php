<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Halaman Login
     */
    public function index()
    {
        return view('public.login.index');
    }

    /**
     * Proses Login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([

            'email'    => ['required', 'email'],

            'password' => ['required'],

        ]);

        if (! Auth::attempt($credentials)) {

            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Email atau password salah.');

        }

        $request->session()->regenerate();

        return redirect()->route('back-office.dashboard');
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}