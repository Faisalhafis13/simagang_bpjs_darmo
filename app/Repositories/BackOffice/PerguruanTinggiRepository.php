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
                    'pengajuan_count' => 0,
                    'peserta_count' => 0,
                    'statuses' => [],
                    'peserta_list' => [],
                ];
            }

            $universitas[$key]['pengajuan_count']++;
            $universitas[$key]['peserta_count'] +=
                1 + $pengajuan->anggota->count();
        }

        $data = array_values(array_map(function ($item) {
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
