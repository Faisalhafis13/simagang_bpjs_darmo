<?php

namespace App\Repositories\Peserta;

use App\Models\Logbook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LogbookRepository
{
    public function index()
    {
        return view('peserta.logbook.index');
    }

    public function getData()
    {
        $data = Logbook::where('user_id', Auth::id())
            ->latest()
            ->get()
            ->map(function ($logbook) {
                return [
                    'id' => $logbook->id,
                    'tanggal' => $logbook->tanggal?->format('Y-m-d'),
                    'aktivitas' => $logbook->aktivitas,
                    'hasil' => $logbook->hasil,
                    'catatan' => $logbook->catatan,

                    'bukti' => $logbook->bukti,

                    'bukti_url' => $logbook->bukti
                        ? Storage::url($logbook->bukti)
                        : null,

                    'status' => $logbook->status ?? 'Menunggu',

                    'catatan_mentor' => $logbook->catatan_mentor,
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
            'tanggal' => 'required|date',
            'aktivitas' => 'required|string',
            'hasil' => 'required|string',
            'catatan' => 'nullable|string',
            'bukti' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $bukti = null;

        if ($request->hasFile('bukti')) {
            $bukti = $request->file('bukti')
                ->store('logbook/bukti', 'public');
        }

        Logbook::create([
            'user_id' => Auth::id(),
            'tanggal' => $data['tanggal'],
            'aktivitas' => $data['aktivitas'],
            'hasil' => $data['hasil'],
            'catatan' => $data['catatan'] ?? null,
            'bukti' => $bukti,

            // Logbook baru selalu menunggu review mentor
            'status' => 'Menunggu',

            'catatan_mentor' => null,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Logbook berhasil ditambahkan.',
        ]);
    }

    public function show($id)
    {
        $logbook = Logbook::where('user_id', Auth::id())
            ->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $logbook->id,
                'tanggal' => $logbook->tanggal?->format('Y-m-d'),
                'aktivitas' => $logbook->aktivitas,
                'hasil' => $logbook->hasil,
                'catatan' => $logbook->catatan,

                'bukti' => $logbook->bukti,

                'bukti_url' => $logbook->bukti
                    ? Storage::url($logbook->bukti)
                    : null,

                'status' => $logbook->status ?? 'Menunggu',

                'catatan_mentor' => $logbook->catatan_mentor,
            ],
        ]);
    }

    public function update(Request $request, $id)
    {
        $logbook = Logbook::where('user_id', Auth::id())
            ->findOrFail($id);

        /*
         * Logbook yang sudah disetujui mentor
         * tidak boleh diubah.
         *
         * Ini adalah pengamanan backend.
         * Jadi meskipun tombol Edit di frontend
         * di-disable, user tetap tidak bisa
         * mengubah data melalui request manual.
         */
        if (strtolower($logbook->status ?? '') === 'disetujui') {
            return response()->json([
                'status' => 'error',
                'message' => 'Logbook sudah disetujui mentor dan tidak dapat diubah lagi.',
            ], 403);
        }

        $data = $request->validate([
            'tanggal' => 'required|date',
            'aktivitas' => 'required|string',
            'hasil' => 'required|string',
            'catatan' => 'nullable|string',
            'bukti' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $updateData = [
            'tanggal' => $data['tanggal'],
            'aktivitas' => $data['aktivitas'],
            'hasil' => $data['hasil'],
            'catatan' => $data['catatan'] ?? null,
        ];

        /*
         * Jika peserta mengganti bukti,
         * hapus file bukti lama terlebih dahulu.
         */
        if ($request->hasFile('bukti')) {

            if ($logbook->bukti) {
                Storage::disk('public')->delete($logbook->bukti);
            }

            $updateData['bukti'] = $request->file('bukti')
                ->store('logbook/bukti', 'public');
        }

        /*
         * Karena logbook yang masuk ke method ini
         * pasti belum disetujui, setelah diedit
         * statusnya tetap Menunggu.
         */
        $updateData['status'] = 'Menunggu';

        $logbook->update($updateData);

        return response()->json([
            'status' => 'success',
            'message' => 'Logbook berhasil diperbarui.',
        ]);
    }

    public function destroy($id)
    {
        $logbook = Logbook::where('user_id', Auth::id())
            ->findOrFail($id);

        /*
         * Logbook yang sudah disetujui mentor
         * tidak boleh dihapus.
         */
        if (strtolower($logbook->status ?? '') === 'disetujui') {
            return response()->json([
                'status' => 'error',
                'message' => 'Logbook sudah disetujui mentor dan tidak dapat dihapus.',
            ], 403);
        }

        /*
         * Hapus file bukti dari storage
         * sebelum menghapus data logbook.
         */
        if ($logbook->bukti) {
            Storage::disk('public')->delete($logbook->bukti);
        }

        $logbook->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logbook berhasil dihapus.',
        ]);
    }
}
