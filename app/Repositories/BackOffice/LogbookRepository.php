<?php

namespace App\Repositories\BackOffice;

use App\Models\AnggotaMagang;
use App\Models\Logbook;
use Illuminate\Http\Request;

class LogbookRepository
{
    public function index()
    {
        $peserta = AnggotaMagang::orderBy('nama_anggota')->get();
        return view('back-office.logbook.index', compact('peserta'));
    }

    public function getData()
    {
        $entries = Logbook::with('anggota.pengajuan')->latest()->get();

        $data = $entries->map(function ($entry) {
            return [
                'id' => $entry->id,
                'tanggal' => $entry->tanggal->format('Y-m-d'),
                'nama_peserta' => $entry->anggota->nama_anggota,
                'email' => $entry->anggota->email,
                'no_hp' => $entry->anggota->no_hp,
                'universitas' => optional($entry->anggota->pengajuan)->universitas,
                'aktivitas' => $entry->aktivitas,
                'hasil' => $entry->hasil,
                'catatan' => $entry->catatan,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'anggota_magang_id' => 'required|exists:anggota_magangs,id',
            'tanggal' => 'required|date',
            'aktivitas' => 'required|string|max:500',
            'hasil' => 'required|string|max:500',
            'catatan' => 'nullable|string|max:1000',
        ]);

        Logbook::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Logbook berhasil ditambahkan.',
        ]);
    }

    public function destroy($id)
    {
        Logbook::findOrFail($id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logbook berhasil dihapus.',
        ]);
    }
}
