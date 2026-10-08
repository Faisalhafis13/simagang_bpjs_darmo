<?php

namespace App\Repositories\BackOffice;

use App\Models\PengajuanMagang;

class PerguruanTinggiRepository
{
    public function index()
    {
        return view('back-office.perguruan-tinggi.index');
    }
    public function getData()
    {
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
            $jumlahPeserta = 1 + $pengajuan->anggota->count();
            if (is_null($pengajuan->archived_at)) {
                $universitas[$key]['pengajuan_aktif']++;
                $universitas[$key]['peserta_aktif'] += $jumlahPeserta;
            } else {
                $universitas[$key]['pengajuan_nonaktif']++;
                $universitas[$key]['peserta_nonaktif'] += $jumlahPeserta;
            }
            $universitas[$key]['pengajuan_total']++;
            $universitas[$key]['peserta_total'] += $jumlahPeserta;
        }
        $data = array_values($universitas);
        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }
}