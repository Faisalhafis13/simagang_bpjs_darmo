<?php

namespace App\Repositories\BackOffice;

use App\Helpers\ActivityLogger;
use App\Models\PengajuanMagang;

class PerguruanTinggiRepository
{
    public function index()
    {
        ActivityLogger::log(
            'Perguruan Tinggi',
            'VIEW',
            'Membuka halaman data perguruan tinggi',
            null,
            null
        );

        return view('back-office.perguruan-tinggi.index');
    }

    public function getData()
    {
        /*
        |--------------------------------------------------------------------------
        | Ambil semua pengajuan DITERIMA
        |--------------------------------------------------------------------------
        |
        | Pengajuan aktif / nonaktif ditentukan berdasarkan archived_at:
        |
        | archived_at = NULL     -> Aktif
        | archived_at != NULL   -> Nonaktif
        |
        */

        $pengajuans = PengajuanMagang::with('anggota')
            ->where('status', 'Diterima')
            ->latest()
            ->get();

        $universitas = [];

        foreach ($pengajuans as $pengajuan) {

            $key = trim($pengajuan->universitas);

            if ($key === '') {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Jika universitas belum ada di array
            |--------------------------------------------------------------------------
            */

            if (!isset($universitas[$key])) {

                $universitas[$key] = [
                    'universitas' => $key,

                    // Pengajuan
                    'pengajuan_aktif' => 0,
                    'pengajuan_nonaktif' => 0,
                    'pengajuan_total' => 0,

                    // Peserta
                    'peserta_aktif' => 0,
                    'peserta_nonaktif' => 0,
                    'peserta_total' => 0,
                ];
            }

            /*
            |--------------------------------------------------------------------------
            | Hitung jumlah peserta
            |--------------------------------------------------------------------------
            |
            | 1 ketua + seluruh anggota
            |
            */

            $jumlahPeserta = 1 + $pengajuan->anggota->count();

            /*
            |--------------------------------------------------------------------------
            | Tentukan status aktif / nonaktif
            |--------------------------------------------------------------------------
            */

            if (is_null($pengajuan->archived_at)) {

                // -------------------------------------------------------------
                // PENGAJUAN AKTIF
                // -------------------------------------------------------------

                $universitas[$key]['pengajuan_aktif']++;

                $universitas[$key]['peserta_aktif'] += $jumlahPeserta;

            } else {

                // -------------------------------------------------------------
                // PENGAJUAN NONAKTIF / ARSIP
                // -------------------------------------------------------------

                $universitas[$key]['pengajuan_nonaktif']++;

                $universitas[$key]['peserta_nonaktif'] += $jumlahPeserta;
            }

            /*
            |--------------------------------------------------------------------------
            | Total
            |--------------------------------------------------------------------------
            */

            $universitas[$key]['pengajuan_total']++;

            $universitas[$key]['peserta_total'] += $jumlahPeserta;
        }

        /*
        |--------------------------------------------------------------------------
        | Ubah associative array menjadi indexed array
        |--------------------------------------------------------------------------
        */

        $data = array_values($universitas);

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }
}