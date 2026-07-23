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
                    'pengajuan_count' => 0,
                    'peserta_count' => 0,
                    'statuses' => [],
                    'peserta_list' => [],
                ];
            }

            $universitas[$key]['pengajuan_count']++;
            $universitas[$key]['peserta_count'] += 1 + $pengajuan->anggota->count();
            $universitas[$key]['statuses'][] = $pengajuan->status;
            $universitas[$key]['peserta_list'][] = $pengajuan->nama_ketua;

            foreach ($pengajuan->anggota as $anggota) {
                $universitas[$key]['peserta_list'][] = $anggota->nama_anggota;
            }
        }

        $data = array_values(array_map(function ($item) {
            $item['status'] = implode(', ', array_unique($item['statuses']));
            $item['peserta_preview'] = implode(', ', array_slice($item['peserta_list'], 0, 5));
            if (count($item['peserta_list']) > 5) {
                $item['peserta_preview'] .= '...';
            }
            unset($item['statuses']);
            unset($item['peserta_list']);
            return $item;
        }, $universitas));

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }
}
