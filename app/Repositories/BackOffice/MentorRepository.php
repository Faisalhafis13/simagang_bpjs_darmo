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
    $emailPesertaAktif = $this->emailPesertaAktif();

    $mentors = Mentor::latest()->get();

    $data = $mentors->map(function ($mentor) use ($emailPesertaAktif) {

        $pesertaNames = User::where('mentor_id', $mentor->id)
            ->whereIn('email', $emailPesertaAktif)
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

        /*
        |--------------------------------------------------------------------------
        | Activity Log
        |--------------------------------------------------------------------------
        */

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

$emailPesertaAktif = $this->emailPesertaAktif();

$peserta = User::where('role_id', $rolePeserta->id)
    ->whereIn('email', $emailPesertaAktif)
    ->select(
        'id',
        'name',
        'email',
        'mentor_id'
    )
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

        /*
        |--------------------------------------------------------------------------
        | Simpan data lama mentor
        |--------------------------------------------------------------------------
        */

        $oldMentorData = $mentor->toArray();

        /*
        |--------------------------------------------------------------------------
        | Simpan peserta lama
        |--------------------------------------------------------------------------
        */

        $oldPeserta = User::where('mentor_id', $mentor->id)
            ->select(
                'id',
                'name',
                'email',
                'mentor_id'
            )
            ->orderBy('name')
            ->get()
            ->toArray();

        $data = $request->validate([
            'nama_mentor' => 'required|string|max:255',
            'divisi' => 'required|string|max:255',
            'peserta' => 'nullable|array',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Update data mentor
        |--------------------------------------------------------------------------
        */

        $mentor->update([
            'nama_mentor' => $data['nama_mentor'],
            'divisi' => $data['divisi'],
            'tugas' => '',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Hapus semua peserta yang sebelumnya dimiliki mentor ini
        |--------------------------------------------------------------------------
        */

        User::where('mentor_id', $mentor->id)
            ->update([
                'mentor_id' => null
            ]);

        /*
        |--------------------------------------------------------------------------
        | Pasangkan peserta yang dipilih
        |--------------------------------------------------------------------------
        */

        if (!empty($data['peserta'])) {

            User::whereIn('id', $data['peserta'])
                ->update([
                    'mentor_id' => $mentor->id
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Ambil peserta setelah perubahan
        |--------------------------------------------------------------------------
        */

        $newPeserta = User::where('mentor_id', $mentor->id)
            ->select(
                'id',
                'name',
                'email',
                'mentor_id'
            )
            ->orderBy('name')
            ->get()
            ->toArray();

        /*
        |--------------------------------------------------------------------------
        | Activity Log
        |--------------------------------------------------------------------------
        */

        ActivityLogger::log(
            'Mentor',
            'UPDATE',
            'Mengubah Mentor dan Penugasan Peserta',
            [
                'mentor' => $oldMentorData,
                'peserta' => $oldPeserta,
            ],
            [
                'mentor' => $mentor->fresh()->toArray(),
                'peserta' => $newPeserta,
            ]
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Mentor berhasil diperbarui.',
        ]);
    }

    public function destroy($id)
    {
        $mentor = Mentor::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Simpan data mentor lama
        |--------------------------------------------------------------------------
        */

        $oldMentorData = $mentor->toArray();

        /*
        |--------------------------------------------------------------------------
        | Simpan peserta yang dimiliki mentor
        |--------------------------------------------------------------------------
        */

        $oldPeserta = User::where('mentor_id', $mentor->id)
            ->select(
                'id',
                'name',
                'email',
                'mentor_id'
            )
            ->orderBy('name')
            ->get()
            ->toArray();

        /*
        |--------------------------------------------------------------------------
        | Hapus mentor
        |--------------------------------------------------------------------------
        */

        $mentor->delete();

        /*
        |--------------------------------------------------------------------------
        | Activity Log
        |--------------------------------------------------------------------------
        */

        ActivityLogger::log(
            'Mentor',
            'DELETE',
            'Menghapus Mentor',
            [
                'mentor' => $oldMentorData,
                'peserta' => $oldPeserta,
            ],
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

    if (!$rolePeserta) {
        return response()->json([
            'status' => 'success',
            'data' => [],
        ]);
    }

    $emailPesertaAktif = $this->emailPesertaAktif();

    $peserta = User::where('role_id', $rolePeserta->id)
        ->whereIn('email', $emailPesertaAktif)
        ->select(
            'id',
            'name',
            'email',
            'mentor_id'
        )
        ->orderBy('name')
        ->get();

    return response()->json([
        'status' => 'success',
        'data' => $peserta,
    ]);
}
    private function emailPesertaAktif()
{
    $pengajuan = PengajuanMagang::where('status', 'Diterima')
        ->whereNull('archived_at')
        ->with('anggota')
        ->get();

    $emailKetua = $pengajuan
        ->pluck('email_ketua');

    $emailAnggota = $pengajuan
        ->pluck('anggota')
        ->flatten()
        ->pluck('email');

    return $emailKetua
        ->merge($emailAnggota)
        ->filter()
        ->unique()
        ->values();
}
}