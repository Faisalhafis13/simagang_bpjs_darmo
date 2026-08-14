<?php

namespace App\Repositories\BackOffice;

use App\Helpers\ActivityLogger;
use App\Models\PengajuanMagang;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
class PengajuanRepository
{
    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        return view('back-office.pengajuan.index');
    }

    /*
    |--------------------------------------------------------------------------
    | Get Data Pengajuan Aktif
    |--------------------------------------------------------------------------
    |
    | Semua pengajuan yang belum diarsipkan tetap tampil.
    |
    | Termasuk:
    | - Pending
    | - Diterima
    | - Ditolak
    |
    | Arsip hanya ditentukan oleh archived_at.
    |
    */

    public function getData()
    {
        $pengajuan = PengajuanMagang::query()
            ->with([
                'anggota',
                'mentor',
                'logbooks',
            ])
            ->whereNull('archived_at')
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $pengajuan,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Update Pengajuan
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'status' => [
                'required',
                'in:menunggu,Menunggu,pending,Pending,diterima,Diterima,accepted,Accepted,ditolak,Ditolak,rejected,Rejected'
            ],

            'catatan' => [
                'nullable',
                'string',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Normalisasi Status
        |--------------------------------------------------------------------------
        */

        $statusMap = [
            'menunggu' => 'Pending',
            'pending' => 'Pending',

            'diterima' => 'Diterima',
            'accepted' => 'Diterima',

            'ditolak' => 'Ditolak',
            'rejected' => 'Ditolak',
        ];

        $statusKey = strtolower($data['status']);

        $status = $statusMap[$statusKey]
            ?? $data['status'];

        /*
        |--------------------------------------------------------------------------
        | Ambil Pengajuan
        |--------------------------------------------------------------------------
        */

        $pengajuan = PengajuanMagang::query()
            ->with([
                'anggota',
                'mentor',
                'logbooks',
            ])
            ->findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Pengajuan Sudah Diarsipkan
        |--------------------------------------------------------------------------
        */

        if ($pengajuan->isArchived()) {
            return response()->json([
                'status' => 'error',
                'message' =>
                    'Pengajuan ini sudah masuk arsip dan tidak dapat diubah dari halaman pengajuan aktif.',
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Pengajuan Sudah Memiliki Keputusan
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $pengajuan->status,
                ['Diterima', 'Ditolak']
            )
        ) {
            return response()->json([
                'status' => 'error',
                'message' =>
                    'Pengajuan ini sudah memiliki keputusan dan tidak dapat diubah lagi.',
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Data Lama
        |--------------------------------------------------------------------------
        */

        $oldData = $pengajuan->toArray();

        /*
        |--------------------------------------------------------------------------
        | UPDATE STATUS
        |--------------------------------------------------------------------------
        |
        | PENTING:
        |
        | Tidak ada lagi pengisian archived_at di sini.
        |
        | Baik Diterima maupun Ditolak tetap berada di halaman Pengajuan
        | sampai admin menekan tombol Arsipkan.
        |
        */

        $pengajuan->update([
            'status' => $status,

            'catatan' => array_key_exists(
                'catatan',
                $data
            )
                ? $data['catatan']
                : $pengajuan->catatan,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Jika Diterima, Buat Akun Peserta
        |--------------------------------------------------------------------------
        */

        if ($status === 'Diterima') {

            /*
            |--------------------------------------------------------------------------
            | Akun Ketua
            |--------------------------------------------------------------------------
            */

            $this->createPesertaAccount(
                $pengajuan->nama_ketua,
                $pengajuan->email_ketua,
                $pengajuan->kode_pengajuan
            );

            /*
            |--------------------------------------------------------------------------
            | Akun Anggota
            |--------------------------------------------------------------------------
            */

            foreach ($pengajuan->anggota as $anggota) {

                if (empty($anggota->email)) {
                    continue;
                }

                $this->createPesertaAccount(
                    $anggota->nama_anggota,
                    $anggota->email,
                    $pengajuan->kode_pengajuan
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Activity Log
        |--------------------------------------------------------------------------
        */

        ActivityLogger::log(
            'Pengajuan Magang',
            'UPDATE',
            'Mengubah status pengajuan magang',
            $oldData,
            $pengajuan
                ->fresh()
                ->toArray()
        );

        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        $message = $status === 'Ditolak'
            ? 'Pengajuan berhasil ditolak. Pengajuan belum diarsipkan.'
            : (
                $status === 'Diterima'
                    ? 'Pengajuan berhasil diterima. Pengajuan belum diarsipkan.'
                    : 'Status pengajuan berhasil diperbarui.'
            );

        return response()->json([
            'status' => 'success',

            'message' => $message,

            'data' => $pengajuan->fresh(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Arsipkan Pengajuan Secara Manual
    |--------------------------------------------------------------------------
    |
    | Pengajuan hanya boleh diarsipkan jika sudah mempunyai keputusan:
    |
    | - Diterima
    | - Ditolak
    |
    | Pending tidak boleh langsung diarsipkan.
    |
    */

    public function archive($id)
    {
        $pengajuan = PengajuanMagang::query()
            ->with([
                'anggota',
                'mentor',
                'logbooks',
            ])
            ->findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Sudah Diarsipkan
        |--------------------------------------------------------------------------
        */

        if ($pengajuan->isArchived()) {
            return response()->json([
                'status' => 'error',
                'message' =>
                    'Pengajuan ini sudah diarsipkan.',
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Harus Sudah Memiliki Keputusan
        |--------------------------------------------------------------------------
        */

        if (
            !in_array(
                $pengajuan->status,
                ['Diterima', 'Ditolak']
            )
        ) {
            return response()->json([
                'status' => 'error',
                'message' =>
                    'Pengajuan yang masih menunggu keputusan tidak dapat diarsipkan.',
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Data Lama
        |--------------------------------------------------------------------------
        */

        $oldData = $pengajuan->toArray();

        /*
        |--------------------------------------------------------------------------
        | Arsipkan
        |--------------------------------------------------------------------------
        */

        $pengajuan->update([
            'archived_at' => now(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Activity Log
        |--------------------------------------------------------------------------
        */

        ActivityLogger::log(
            'Pengajuan Magang',
            'ARCHIVE',
            'Mengarsipkan pengajuan magang secara manual',
            $oldData,
            $pengajuan
                ->fresh()
                ->toArray()
        );

        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'status' => 'success',

            'message' =>
                'Pengajuan berhasil diarsipkan.',

            'data' =>
                $pengajuan->fresh(),
        ]);
    }

    /*
|--------------------------------------------------------------------------
| Tampilkan File Pengajuan
|--------------------------------------------------------------------------
*/

public function file($id, $type)
{
    $allowedTypes = [

        'proposal' => [
            'column' => 'proposal',
            'folders' => [
                'proposal',
            ],
        ],

        'surat-permohonan' => [
            'column' => 'surat_permohonan',
            'folders' => [
                'surat_permohonan',
                'surat-permohonan',
            ],
        ],

        'surat-penerimaan' => [
            'column' => 'surat_penerimaan',
            'folders' => [
                'surat-penerimaan',
                'surat_penerimaan',
            ],
        ],
    ];

    abort_unless(
        isset($allowedTypes[$type]),
        404,
        'Jenis dokumen tidak valid.'
    );

    $pengajuan = PengajuanMagang::findOrFail($id);

    $config = $allowedTypes[$type];

    $databasePath = $pengajuan->{$config['column']};

    abort_if(
        empty($databasePath),
        404,
        'Dokumen tidak tersedia.'
    );

    $disk = Storage::disk('public');

    $databasePath = str_replace('\\', '/', trim($databasePath));
    $databasePath = ltrim($databasePath, '/');

    $candidates = [];

    $candidates[] = $databasePath;

    if (str_starts_with(
        strtolower($databasePath),
        'storage/'
    )) {
        $candidates[] = substr(
            $databasePath,
            strlen('storage/')
        );
    }

    if (str_starts_with(
        strtolower($databasePath),
        'public/'
    )) {
        $candidates[] = substr(
            $databasePath,
            strlen('public/')
        );
    }

    $basename = basename($databasePath);

    foreach ($config['folders'] as $folder) {
        $candidates[] = $folder . '/' . $basename;
    }

    $candidates = array_values(
        array_unique(
            array_filter($candidates)
        )
    );

    $foundPath = null;

    foreach ($candidates as $candidate) {

        $candidate = str_replace(
            '\\',
            '/',
            trim($candidate)
        );

        $candidate = ltrim(
            $candidate,
            '/'
        );

        if (str_starts_with(
            strtolower($candidate),
            'storage/'
        )) {
            $candidate = substr(
                $candidate,
                strlen('storage/')
            );
        }

        if (str_starts_with(
            strtolower($candidate),
            'public/'
        )) {
            $candidate = substr(
                $candidate,
                strlen('public/')
            );
        }

        if ($disk->exists($candidate)) {
            $foundPath = $candidate;
            break;
        }
    }

    if (!$foundPath) {
        abort(
            404,
            'File dokumen tidak ditemukan.'
        );
    }

    $fullPath = $disk->path($foundPath);

    $mimeType = $disk->mimeType($foundPath)
        ?: 'application/octet-stream';

    $fileName = basename($foundPath);

    return response()->file(
        $fullPath,
        [
            'Content-Type' => $mimeType,

            'Content-Disposition' =>
                'inline; filename="' .
                $fileName .
                '"',
        ]
    );
}
    /*
    |--------------------------------------------------------------------------
    | Create Peserta Account
    |--------------------------------------------------------------------------
    */

    protected function createPesertaAccount(
        $nama,
        $email,
        $passwordAwal
    ): void {

        if (empty($email)) {
            return;
        }

        $role = Role::firstOrCreate([
            'name' => 'Peserta',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Cari User berdasarkan Email
        |--------------------------------------------------------------------------
        */

        $user = User::where(
            'email',
            $email
        )->first();

        /*
        |--------------------------------------------------------------------------
        | Buat User Baru
        |--------------------------------------------------------------------------
        */

        if (!$user) {

            $user = User::create([
                'name' => $nama,

                'email' => $email,

                'password' =>
                    Hash::make($passwordAwal),

                'role_id' => $role->id,

                'must_change_password' => true,
            ]);

            ActivityLogger::log(
                'Peserta',
                'CREATE',
                'Membuat akun peserta dari pengajuan magang',
                null,
                $user->fresh()->toArray()
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Update User Lama
        |--------------------------------------------------------------------------
        */

        $oldUserData = $user->toArray();

        $user->update([
            'name' => $nama,

            'role_id' => $role->id,

            'password' =>
                Hash::make($passwordAwal),

            'must_change_password' => true,
        ]);

        ActivityLogger::log(
            'Peserta',
            'UPDATE',
            'Memperbarui akun peserta dari pengajuan magang',
            $oldUserData,
            $user->fresh()->toArray()
        );
    }
}