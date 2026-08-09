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

        'email' => ['required', 'email'],

        'password' => ['required'],

    ]);


    /*
    |--------------------------------------------------------------------------
    | CEK LOGIN
    |--------------------------------------------------------------------------
    */

    if (!Auth::attempt($credentials)) {

        return back()

            ->withInput(
                $request->only('email')
            )

            ->with(
                'error',
                'Email atau password salah.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | REGENERATE SESSION
    |--------------------------------------------------------------------------
    */

    $request->session()->regenerate();


    /*
    |--------------------------------------------------------------------------
    | LOG ACTIVITY
    |--------------------------------------------------------------------------
    */

    ActivityLogger::log(
        'Authentication',
        'LOGIN',
        'User Login'
    );


    /*
    |--------------------------------------------------------------------------
    | USER WAJIB GANTI PASSWORD
    |--------------------------------------------------------------------------
    */

    if (Auth::user()->must_change_password) {

        return redirect()
            ->route('password.change')
            ->with(
                'login_success',
                'Login berhasil. Silakan ubah password Anda.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | LOGIN BERHASIL
    |--------------------------------------------------------------------------
    */

    return redirect()
        ->route('back-office.dashboard')
        ->with(
            'login_success',
            'Selamat datang, ' . Auth::user()->name . '!'
        );
}
public function logout(Request $request)
{
    /*
    |--------------------------------------------------------------------------
    | LOG ACTIVITY SEBELUM LOGOUT
    |--------------------------------------------------------------------------
    */

    ActivityLogger::log(
        'Authentication',
        'LOGOUT',
        'User Logout'
    );


    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    Auth::logout();


    /*
    |--------------------------------------------------------------------------
    | INVALIDATE SESSION
    |--------------------------------------------------------------------------
    */

    $request->session()->invalidate();

    $request->session()->regenerateToken();


    /*
    |--------------------------------------------------------------------------
    | KEMBALI KE LOGIN
    |--------------------------------------------------------------------------
    */

    return redirect()
        ->route('login')
        ->with(
            'logout_success',
            'Anda telah berhasil keluar dari sistem.'
        );
}
}
