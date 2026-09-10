<?php

namespace App\Repositories\Peserta;

use App\Models\AnggotaMagang;
use App\Models\PengajuanMagang;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PesertaRepository
{
    public function index()
    {
        $user = Auth::user();
        $mentorUser = $user
            ? $user->mentor
            : null;
        $pengajuanKetua = PengajuanMagang::with([
            'anggota',
            'mentor',
        ])
            ->where('email_ketua', $user->email)
            ->where('status', 'Diterima')
            ->latest()
            ->first();
        $anggota = null;
        $pengajuanAnggota = null;
        if (! $pengajuanKetua) {
            $anggota = AnggotaMagang::with([
                'pengajuan.anggota',
                'pengajuan.mentor',
            ])
                ->where('email', $user->email)
                ->latest()
                ->first();
            if ($anggota) {
                $pengajuanAnggota = $anggota->pengajuan;
            }
        }
        $pengajuan =
            $pengajuanKetua
            ?? $pengajuanAnggota;
        $peserta = null;
        if ($pengajuan) {
            if ($pengajuanKetua) {
                $peserta = [
                    'nama' => $pengajuan->nama_ketua,
                    'email' => $pengajuan->email_ketua,
                    'no_hp' => $pengajuan->no_hp,
                    'peran' => 'Ketua',
                ];
            } elseif ($anggota) {
                $peserta = [
                    'nama' => $anggota->nama_anggota,
                    'email' => $anggota->email,
                    'no_hp' => $anggota->no_hp,
                    'peran' => 'Anggota',
                ];
            }
        }
        $mentor =
            $mentorUser
            ?? ($pengajuan?->mentor);
        $statusWaktuMagang = null;
        $sisaHariMagang = null;
        $totalHariMagang = null;
        $hariBerjalan = null;
        if (
            $pengajuan &&
            $pengajuan->tanggal_mulai &&
            $pengajuan->tanggal_selesai
        ) {
            $tanggalMulai = Carbon::parse(
                $pengajuan->tanggal_mulai
            )->startOfDay();
            $tanggalSelesai = Carbon::parse(
                $pengajuan->tanggal_selesai
            )->startOfDay();
            $hariIni = Carbon::today();
            $totalHariMagang =
                $tanggalMulai->diffInDays(
                    $tanggalSelesai
                ) + 1;
            if ($hariIni->lt($tanggalMulai)) {
                $statusWaktuMagang = 'belum_mulai';
                $sisaHariMagang =
                    $hariIni->diffInDays(
                        $tanggalMulai
                    );
            } elseif ($hariIni->lte($tanggalSelesai)) {
                $statusWaktuMagang = 'berlangsung';
                $sisaHariMagang =
                    $hariIni->diffInDays(
                        $tanggalSelesai
                    );
                $hariBerjalan =
                    $tanggalMulai->diffInDays(
                        $hariIni
                    ) + 1;
            } else {
                $statusWaktuMagang = 'selesai';
                $sisaHariMagang = 0;
                $hariBerjalan =
                    $totalHariMagang;
            }
        }
        return view(
            'peserta.peserta.index',
            [
                'user' => $user,
                'peserta' => $peserta,
                'pengajuan' => $pengajuan,
                'mentor' => $mentor,
                'statusWaktuMagang' => $statusWaktuMagang,
                'sisaHariMagang' => $sisaHariMagang,
                'totalHariMagang' => $totalHariMagang,
                'hariBerjalan' => $hariBerjalan,
            ]
        );
    }
}
