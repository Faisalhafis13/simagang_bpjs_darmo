<?php

namespace App\Repositories\BackOffice;

use App\Models\AnggotaMagang;

class PesertaRepository
{
    public function index()
    {
        return view('back-office.peserta.index');
    }

    public function getData()
    {
        $pengajuan = \App\Models\PengajuanMagang::with('anggota')
            ->latest()
            ->get();

        $peserta = [];

        foreach ($pengajuan as $pengajuanItem) {
            $peserta[] = [
                'id' => 'ketua-' . $pengajuanItem->id,
                'nama_peserta' => $pengajuanItem->nama_ketua,
                'email' => $pengajuanItem->email_ketua,
                'no_hp' => $pengajuanItem->no_hp,
                'kode_pengajuan' => $pengajuanItem->kode_pengajuan,
                'universitas' => $pengajuanItem->universitas,
                'status' => $pengajuanItem->status,
            ];

            foreach ($pengajuanItem->anggota as $anggota) {
                $peserta[] = [
                    'id' => 'anggota-' . $anggota->id,
                    'nama_peserta' => $anggota->nama_anggota,
                    'email' => $anggota->email,
                    'no_hp' => $anggota->no_hp,
                    'kode_pengajuan' => $pengajuanItem->kode_pengajuan,
                    'universitas' => $pengajuanItem->universitas,
                    'status' => $pengajuanItem->status,
                ];
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => $peserta,
        ]);
    }
}
