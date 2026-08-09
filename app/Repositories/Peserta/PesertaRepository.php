<?php

namespace App\Repositories\Peserta;

use App\Models\AnggotaMagang;
use App\Models\PengajuanMagang;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PesertaRepository
{
    /**
     * Halaman data peserta
     */
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | User yang sedang login
        |--------------------------------------------------------------------------
        */

        $user = Auth::user();


        /*
        |--------------------------------------------------------------------------
        | Ambil mentor dari akun User
        |--------------------------------------------------------------------------
        */

        $mentorUser = $user
            ? $user->mentor
            : null;


        /*
        |--------------------------------------------------------------------------
        | Cari peserta sebagai Ketua
        |--------------------------------------------------------------------------
        */

        $pengajuanKetua = PengajuanMagang::with([
            'anggota',
            'mentor',
        ])
            ->where('email_ketua', $user->email)
            ->where('status', 'Diterima')
            ->latest()
            ->first();


        /*
        |--------------------------------------------------------------------------
        | Kalau bukan Ketua, cari sebagai Anggota
        |--------------------------------------------------------------------------
        */

        $anggota = null;

        $pengajuanAnggota = null;

        if (!$pengajuanKetua) {

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


        /*
        |--------------------------------------------------------------------------
        | Tentukan pengajuan peserta
        |--------------------------------------------------------------------------
        */

        $pengajuan =
            $pengajuanKetua
            ?? $pengajuanAnggota;


        /*
        |--------------------------------------------------------------------------
        | Data peserta
        |--------------------------------------------------------------------------
        */

        $peserta = null;

        if ($pengajuan) {

            if ($pengajuanKetua) {

                $peserta = [

                    'nama' =>
                        $pengajuan->nama_ketua,

                    'email' =>
                        $pengajuan->email_ketua,

                    'no_hp' =>
                        $pengajuan->no_hp,

                    'peran' =>
                        'Ketua',

                ];

            } elseif ($anggota) {

                $peserta = [

                    'nama' =>
                        $anggota->nama_anggota,

                    'email' =>
                        $anggota->email,

                    'no_hp' =>
                        $anggota->no_hp,

                    'peran' =>
                        'Anggota',

                ];
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Mentor
        |--------------------------------------------------------------------------
        */

        $mentor =
            $mentorUser
            ?? ($pengajuan?->mentor);


        /*
        |--------------------------------------------------------------------------
        | INFORMASI WAKTU MAGANG
        |--------------------------------------------------------------------------
        */

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


            /*
            |--------------------------------------------------------------------------
            | Total durasi magang
            |--------------------------------------------------------------------------
            */

            $totalHariMagang =
                $tanggalMulai->diffInDays(
                    $tanggalSelesai
                ) + 1;


            /*
            |--------------------------------------------------------------------------
            | Belum mulai
            |--------------------------------------------------------------------------
            */

            if ($hariIni->lt($tanggalMulai)) {

                $statusWaktuMagang = 'belum_mulai';

                $sisaHariMagang =
                    $hariIni->diffInDays(
                        $tanggalMulai
                    );


            /*
            |--------------------------------------------------------------------------
            | Sedang berlangsung
            |--------------------------------------------------------------------------
            */

            } elseif ($hariIni->lte($tanggalSelesai)) {

                $statusWaktuMagang = 'berlangsung';

                /*
                | Hari yang tersisa termasuk hari ini.
                */

                $sisaHariMagang =
                    $hariIni->diffInDays(
                        $tanggalSelesai
                    );

                /*
                | Hari yang sudah berjalan.
                */

                $hariBerjalan =
                    $tanggalMulai->diffInDays(
                        $hariIni
                    ) + 1;


            /*
            |--------------------------------------------------------------------------
            | Sudah selesai
            |--------------------------------------------------------------------------
            */

            } else {

                $statusWaktuMagang = 'selesai';

                $sisaHariMagang = 0;

                $hariBerjalan =
                    $totalHariMagang;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'peserta.peserta.index',
            [

                'user' =>
                    $user,

                'peserta' =>
                    $peserta,

                'pengajuan' =>
                    $pengajuan,

                'mentor' =>
                    $mentor,

                /*
                | Informasi waktu magang
                */

                'statusWaktuMagang' =>
                    $statusWaktuMagang,

                'sisaHariMagang' =>
                    $sisaHariMagang,

                'totalHariMagang' =>
                    $totalHariMagang,

                'hariBerjalan' =>
                    $hariBerjalan,
            ]
        );
    }
}
