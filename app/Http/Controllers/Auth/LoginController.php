<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ActivityLogger;

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
        ActivityLogger::log(

    'Authentication',

    'LOGIN',

    'User Login'

);

        if (Auth::user()->must_change_password) {
            return redirect()->route('password.change');
        }

        return redirect()->route('back-office.dashboard');
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
    ActivityLogger::log(

    'Authentication',

    'LOGOUT',

    'User Logout'

);    
    Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}