<?php

namespace App\Repositories\BackOffice;

use App\Models\Mentor;
use App\Models\PengajuanMagang;
use Illuminate\Http\Request;
use App\Helpers\ActivityLogger;
use App\Models\User;
use App\Models\Role;

class MentorRepository
{
    public function index()
    {
        return view('back-office.mentor.index');
    }

public function getData()
{
    $mentors = Mentor::with('peserta')
        ->latest()
        ->get();

    $data = $mentors->map(function ($mentor) {

        $pesertaNames = $mentor->peserta
            ->pluck('name')
            ->filter()
            ->values()
            ->toArray();

        return [
            'id' => $mentor->id,
            'nama_mentor' => $mentor->nama_mentor,
            'divisi' => $mentor->divisi,
            'peserta_preview' => !empty($pesertaNames)
                ? implode(', ', $pesertaNames)
                : '-',
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
        'nama_mentor' => 'required|string|max:255',
        'divisi' => 'required|string|max:255',
        'peserta' => 'nullable|array',
        'peserta.*' => 'integer|exists:users,id',
    ]);

    // Pastikan tugas tetap tersedia untuk kompatibilitas database
    $data['tugas'] = '';

    // Buat mentor
    $mentor = Mentor::create([
        'nama_mentor' => $data['nama_mentor'],
        'divisi' => $data['divisi'],
        'tugas' => $data['tugas'],
    ]);

    /*
    |--------------------------------------------------------------------------
    | Hubungkan peserta ke mentor
    |--------------------------------------------------------------------------
    */

    if (!empty($data['peserta'])) {

        User::whereIn('id', $data['peserta'])
            ->update([
                'mentor_id' => $mentor->id,
            ]);
    }

    ActivityLogger::log(
        'Mentor',
        'CREATE',
        'Menambah Mentor',
        null,
        $mentor->fresh()->load('peserta')->toArray()
    );

    return response()->json([
        'status' => 'success',
        'message' => 'Mentor berhasil ditambahkan.',
        'data' => $mentor->fresh()->load('peserta'),
    ]);
}
public function show($id)
{
    $mentor = Mentor::findOrFail($id);

    $rolePeserta = Role::where('name', 'Peserta')->first();

    $peserta = collect();

    if ($rolePeserta) {
        $peserta = User::where('role_id', $rolePeserta->id)
            ->select('id', 'name', 'email', 'mentor_id')
            ->orderBy('name')
            ->get();
    }

    return response()->json([
        'status' => 'success',
        'data' => [
            'mentor' => $mentor,
            'peserta' => $peserta,
        ],
    ]);
}
public function update(Request $request, $id)
{
    $mentor = Mentor::findOrFail($id);

    $oldData = $mentor->toArray();

    $data = $request->validate([
        'nama_mentor' => 'required|string|max:255',
        'divisi' => 'required|string|max:255',
        'peserta' => 'nullable|array',
    ]);

    $mentor->update([
        'nama_mentor' => $data['nama_mentor'],
        'divisi' => $data['divisi'],
        'tugas' => '',
    ]);

    // Hapus semua peserta yang sebelumnya dimiliki mentor ini
    User::where('mentor_id', $mentor->id)
        ->update([
            'mentor_id' => null
        ]);

    // Pasangkan peserta yang dipilih
    if (!empty($data['peserta'])) {

        User::whereIn('id', $data['peserta'])
            ->update([
                'mentor_id' => $mentor->id
            ]);

    }

    ActivityLogger::log(
        'Mentor',
        'UPDATE',
        'Mengubah Mentor',
        $oldData,
        $mentor->fresh()->toArray()
    );

    return response()->json([
        'status' => 'success',
        'message' => 'Mentor berhasil diperbarui.',
    ]);
}
    public function destroy($id)
    {
        $mentor = Mentor::findOrFail($id);

        $oldData = $mentor->toArray();

        $mentor->delete();

        ActivityLogger::log(
            'Mentor',
            'DELETE',
            'Menghapus Mentor',
            $oldData,
            null
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Mentor berhasil dihapus.',
        ]);
    }
    public function peserta()
{
    $rolePeserta = Role::where('name', 'Peserta')->first();

    $peserta = User::where('role_id', $rolePeserta->id)
        ->select('id', 'name', 'email', 'mentor_id')
        ->orderBy('name')
        ->get();

    return response()->json([
        'status' => 'success',
        'data' => $peserta
    ]);
}
}