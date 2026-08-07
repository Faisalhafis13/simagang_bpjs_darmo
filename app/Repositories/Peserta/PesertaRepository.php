<?php

namespace App\Repositories\Peserta;

use App\Models\AnggotaMagang;
use App\Models\PengajuanMagang;
use Illuminate\Support\Facades\Auth;

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
        |
        | Mentor peserta disimpan pada:
        |
        | users.mentor_id
        |
        | Relasi sudah tersedia di User.php:
        |
        | public function mentor()
        | {
        |     return $this->belongsTo(Mentor::class);
        | }
        |
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

                $pengajuanAnggota =
                    $anggota->pengajuan;
            }
        }


        $pengajuan =
            $pengajuanKetua
            ?? $pengajuanAnggota;
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

            }
            elseif ($anggota) {

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
        $mentor =
            $mentorUser
            ?? ($pengajuan?->mentor);

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
            ]
        );
    }
}
