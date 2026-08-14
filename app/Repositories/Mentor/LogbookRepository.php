<?php

namespace App\Repositories\Mentor;

use App\Models\Logbook;
use App\Models\Mentor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ActivityLogger;

class LogbookRepository
{
    protected function getMentor()
    {
        $user = Auth::user();

        return Mentor::where(
            'nama_mentor',
            $user->name
        )->first();
    }

public function peserta()
{
    $mentor = $this->getMentor();

    if (!$mentor) {
        return collect([]);
    }

    return $mentor->peserta()
        ->whereHas('logbooks.pengajuan', function ($query) {
            $query->whereNull('archived_at');
        })
        ->select(
            'id',
            'name'
        )
        ->orderBy(
            'name',
            'asc'
        )
        ->get();
}
    public function getData($request)
    {
        $mentor = $this->getMentor();

        if (!$mentor) {
            return collect([]);
        }

return Logbook::with([
        'user',
        'pengajuan',
    ])
    ->whereHas('user', function ($query) use ($mentor) {

        $query->where(
            'mentor_id',
            $mentor->id
        );

    })
    ->whereHas('pengajuan', function ($query) {

        $query->whereNull('archived_at');

    })
    ->where(
        'user_id',
        $request->user_id
    )
    ->orderBy(
        'tanggal',
        'desc'
    )
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
                ? asset('storage/' . $logbook->bukti)
                : null,
            'status' => $logbook->status ?? 'Menunggu',
            'catatan_mentor' => $logbook->catatan_mentor,
        ];

    });
        }

    public function approve(Request $request, $id)
    {
        $mentor = $this->getMentor();

        if (!$mentor) {
            return response()->json([
                'status' => 'error',
                'message' => 'Mentor tidak ditemukan.'
            ], 404);
        }

$logbook = Logbook::with([
        'user',
        'pengajuan',
    ])
    ->whereHas('user', function ($query) use ($mentor) {

        $query->where(
            'mentor_id',
            $mentor->id
        );

    })
    ->whereHas('pengajuan', function ($query) {

        $query->whereNull('archived_at');

    })
    ->findOrFail($id);
        /*
        |--------------------------------------------------------------------------
        | Simpan data lama untuk Activity Log
        |--------------------------------------------------------------------------
        */

        $oldData = $logbook->toArray();

        /*
        |--------------------------------------------------------------------------
        | Update status logbook
        |--------------------------------------------------------------------------
        */

        $logbook->update([
            'status' => 'Disetujui',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Activity Log
        |--------------------------------------------------------------------------
        */

        ActivityLogger::log(
            'Logbook',
            'UPDATE',
            'Menyetujui Logbook Peserta',
            $oldData,
            $logbook->fresh()->load('user')->toArray()
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Logbook berhasil disetujui.',
        ]);
    }

    public function feedback(Request $request, $id)
    {
        $mentor = $this->getMentor();

        if (!$mentor) {
            return response()->json([
                'status' => 'error',
                'message' => 'Mentor tidak ditemukan.'
            ], 404);
        }

        $data = $request->validate([
            'catatan_mentor' => 'nullable|string|max:5000',
        ]);

$logbook = Logbook::with([
        'user',
        'pengajuan',
    ])
    ->whereHas(
        'user',
        function ($query) use ($mentor) {

            $query->where(
                'mentor_id',
                $mentor->id
            );

        }
    )
    ->whereHas('pengajuan', function ($query) {

        $query->whereNull('archived_at');

    })
    ->findOrFail($id);
        /*
        |--------------------------------------------------------------------------
        | Simpan data lama untuk Activity Log
        |--------------------------------------------------------------------------
        */

        $oldData = $logbook->toArray();

        /*
        |--------------------------------------------------------------------------
        | Simpan catatan mentor
        |--------------------------------------------------------------------------
        */

        $logbook->update([
            'catatan_mentor' => $data['catatan_mentor'] ?? null,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Activity Log
        |--------------------------------------------------------------------------
        */

        ActivityLogger::log(
            'Logbook',
            'UPDATE',
            'Memberikan Catatan Mentor pada Logbook',
            $oldData,
            $logbook->fresh()->load('user')->toArray()
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Catatan mentor berhasil disimpan.',
        ]);
    }
}
