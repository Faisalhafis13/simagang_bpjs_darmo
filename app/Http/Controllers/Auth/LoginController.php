<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ActivityLogger;
use App\Models\PengajuanMagang;

class LoginController extends Controller
{
    public function index()
    {
        return view('public.login.index');
    }
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
        $email = $credentials['email'];
        $pengajuanArsip = PengajuanMagang::query()
            ->whereNotNull('archived_at')
            ->where(function ($query) use ($email) {
                $query->where(
                    'email_ketua',
                    $email )
                ->orWhereHas('anggota', function ($anggotaQuery) use ($email) {
                    $anggotaQuery->where(
                        'email',
                        $email );});})
            ->latest('archived_at')
            ->first();
        if ($pengajuanArsip) {
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
        $request->session()->regenerate();
        ActivityLogger::log(
            'Authentication',
            'LOGIN',
            'User Login'
        );
        if (Auth::user()->must_change_password) {
            return redirect()
                ->route('password.change')
                ->with(
                    'login_success',
                    'Login berhasil. Silakan ubah password Anda.'
                );
        }
        return redirect()
            ->route('back-office.dashboard')
            ->with(
                'login_success',
                'Selamat datang, ' . Auth::user()->name . '!'
            );
    }
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
        return redirect()
            ->route('login')
            ->with(
                'logout_success',
                'Anda telah berhasil keluar dari sistem.'
            );
    }
}