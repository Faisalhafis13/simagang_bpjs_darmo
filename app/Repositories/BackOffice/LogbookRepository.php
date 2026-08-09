<?php

namespace App\Repositories\BackOffice;

use App\Models\Logbook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Helpers\ActivityLogger;

class LogbookRepository
{
    public function index()
    {
        return view('back-office.logbook.index');
    }

public function getData()
{
    $entries = Logbook::with(['user.role', 'user.mentor'])
        ->orderByDesc('tanggal')
        ->orderByDesc('id')
        ->get();

    $data = $entries->values()->map(function ($entry, $index) {

        return [
            'no' => $index + 1,

            'id' => $entry->id,

            'tanggal' => optional($entry->tanggal)
                ->format('Y-m-d'),

            'nama_peserta' => optional($entry->user)
                ->name,

            'email' => optional($entry->user)
                ->email,

            'mentor' => optional($entry->user?->mentor)
                ->nama_mentor,

            'aktivitas' => $entry->aktivitas,

            'hasil' => $entry->hasil,

            'catatan' => $entry->catatan,

            /*
            |--------------------------------------------------------------------------
            | BUKTI KEGIATAN
            |--------------------------------------------------------------------------
            */

            'bukti' => $entry->bukti,

            'bukti_url' => $entry->bukti
                ? asset('storage/' . ltrim($entry->bukti, '/'))
                : null,

            /*
            |--------------------------------------------------------------------------
            | STATUS
            |--------------------------------------------------------------------------
            */

            'status' => $entry->status ?? 'Menunggu',

            /*
            |--------------------------------------------------------------------------
            | CATATAN MENTOR
            |--------------------------------------------------------------------------
            */

            'catatan_mentor' => $entry->catatan_mentor,
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
            'user_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'aktivitas' => 'required|string|max:500',
            'hasil' => 'required|string|max:500',
            'catatan' => 'nullable|string|max:1000',
        ]);

        $logbook = Logbook::create($data);

        ActivityLogger::log(
            'Logbook',
            'CREATE',
            'Menambahkan logbook',
            null,
            $logbook->toArray()
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Logbook berhasil ditambahkan.',
        ]);
    }

    public function destroy($id)
    {
        $logbook = Logbook::findOrFail($id);

        $oldData = $logbook->toArray();

        $logbook->delete();

        ActivityLogger::log(
            'Logbook',
            'DELETE',
            'Menghapus logbook',
            $oldData,
            null
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Logbook berhasil dihapus.',
        ]);
    }
}