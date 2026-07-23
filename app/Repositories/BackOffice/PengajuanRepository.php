<?php

namespace App\Repositories\BackOffice;

use App\Models\PengajuanMagang;
use Illuminate\Http\Request;

class PengajuanRepository
{
    public function index()
    {
        return view('back-office.pengajuan.index');
    }

    public function getData()
    {
        $pengajuan = PengajuanMagang::with('anggota')
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $pengajuan,
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'status' => ['required', 'in:menunggu,diterima,ditolak,Pending,Diterima,Ditolak'],
            'catatan' => ['nullable', 'string'],
        ]);

        $pengajuan = PengajuanMagang::findOrFail($id);

        $pengajuan->update([
            'status' => $data['status'],
            'catatan' => $data['catatan'] ?? $pengajuan->catatan,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Status pengajuan berhasil diperbarui.',
            'data' => $pengajuan,
        ]);
    }
}
