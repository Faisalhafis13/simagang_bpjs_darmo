<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ActivityLogger;
use App\Models\PengajuanMagang;

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

            'email' => [
                'required',
                'email',
            ],

            'password' => [
                'required',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | CEK PESERTA YANG SUDAH DIARSIPKAN
        |--------------------------------------------------------------------------
        |
        | Jika email peserta ditemukan pada pengajuan yang sudah diarsipkan,
        | maka peserta dianggap sudah selesai magang dan tidak diperbolehkan
        | login kembali.
        |
        */

        $email = $credentials['email'];

        $pengajuanArsip = PengajuanMagang::query()
            ->whereNotNull('archived_at')
            ->where(function ($query) use ($email) {

                /*
                |--------------------------------------------------------------
                | Email Ketua
                |--------------------------------------------------------------
                */

                $query->where(
                    'email_ketua',
                    $email
                )

                /*
                |--------------------------------------------------------------
                | Email Anggota
                |--------------------------------------------------------------
                */

                ->orWhereHas('anggota', function ($anggotaQuery) use ($email) {

                    $anggotaQuery->where(
                        'email',
                        $email
                    );

                });

            })
            ->latest('archived_at')
            ->first();


        /*
        |--------------------------------------------------------------------------
        | PESERTA SUDAH SELESAI MAGANG
        |--------------------------------------------------------------------------
        */

        if ($pengajuanArsip) {

            /*
            | Tidak melakukan Auth::attempt().
            |
            | Jadi peserta benar-benar tidak dibuat login kembali.
            */

            return back()
                ->withInput(
                    $request->only('email')
                )
                ->with(
                    'magang_selesai',
                    true
                )
                ->with(
                    'magang_selesai_message',
                    'Masa magang Anda telah selesai dan akun Anda sudah tidak dapat digunakan untuk login karena pengajuan magang telah diarsipkan.'
                );

        }


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


    /**
     * Logout
     */
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