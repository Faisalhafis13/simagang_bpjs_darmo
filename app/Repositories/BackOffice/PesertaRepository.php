<?php

namespace App\Repositories\BackOffice;

use App\Models\PengajuanMagang;

class PesertaRepository
{
    public function index()
    {
        return view('back-office.peserta.index');
    }

    public function getData()
    {
        $pengajuan = PengajuanMagang::with('anggota')
            ->where('status', 'Diterima')
            ->latest()
            ->get();

        $peserta = [];

        foreach ($pengajuan as $item) {

            // Ketua
            $peserta[] = [
                'kode_pengajuan' => $item->kode_pengajuan,
                'nama_peserta'   => $item->nama_ketua,
                'email'          => $item->email_ketua,
                'no_hp'          => $item->no_hp,
                'universitas'    => $item->universitas,
                'status'         => $item->status,
            ];

            // Anggota
            foreach ($item->anggota as $anggota) {

                $peserta[] = [
                    'kode_pengajuan' => $item->kode_pengajuan,
                    'nama_peserta'   => $anggota->nama_anggota,
                    'email'          => $anggota->email,
                    'no_hp'          => $anggota->no_hp,
                    'universitas'    => $item->universitas,
                    'status'         => $item->status,
                ];

            }
        }

        return response()->json([
            'data' => $peserta
        ]);
    }
}