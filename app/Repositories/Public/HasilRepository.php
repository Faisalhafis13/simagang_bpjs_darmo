<?php

namespace App\Repositories\Public;

use App\Models\PengajuanMagang;
use Illuminate\Http\Request;

class HasilRepository
{
    public function index()
    {
        return view('public.hasil.index');
    }

public function cari(Request $request)
{
    $request->validate([
        'kode_pengajuan' => 'required|string',
    ]);

    $kode = trim($request->kode_pengajuan);

    $pengajuan = PengajuanMagang::with('anggota')
        ->where('kode_pengajuan', $kode)
        ->first();

    if (!$pengajuan) {

        return response()->json([
            'success' => false,
            'type' => 'not_found',
            'message' => 'Kode pengajuan tidak ditemukan.',
        ], 404);
    }

    if (!is_null($pengajuan->archived_at)) {

        return response()->json([
            'success' => false,
            'type' => 'inactive',
        ], 410);
    }

    return response()->json([
        'success' => true,
        'data' => $pengajuan,
    ]);
}
}