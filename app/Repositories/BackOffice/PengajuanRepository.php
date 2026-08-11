<?php

namespace App\Repositories\BackOffice;

use App\Helpers\ActivityLogger;
use App\Models\PengajuanMagang;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
            'status' => [
                'required',
                'in:menunggu,Menunggu,pending,Pending,diterima,Diterima,accepted,Accepted,ditolak,Ditolak,rejected,Rejected'
            ],
            'catatan' => ['nullable', 'string'],
        ]);

        $statusMap = [
            'menunggu' => 'Pending',
            'pending' => 'Pending',
            'diterima' => 'Diterima',
            'accepted' => 'Diterima',
            'ditolak' => 'Ditolak',
            'rejected' => 'Ditolak',
        ];

        $statusKey = strtolower($data['status']);

        $status = $statusMap[$statusKey] ?? $data['status'];

        $pengajuan = PengajuanMagang::with('anggota')
            ->findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Pengajuan yang sudah diputus tidak boleh diubah lagi
        |--------------------------------------------------------------------------
        */

        if (in_array($pengajuan->status, ['Diterima', 'Ditolak'])) {

            return response()->json([
                'status' => 'error',
                'message' => 'Pengajuan ini sudah memiliki keputusan dan tidak dapat diubah lagi.',
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Simpan data lama untuk activity log
        |--------------------------------------------------------------------------
        */

        $oldData = $pengajuan->toArray();

        /*
        |--------------------------------------------------------------------------
        | Update status
        |--------------------------------------------------------------------------
        */

        $pengajuan->update([
            'status' => $status,
            'catatan' => array_key_exists('catatan', $data)
                ? $data['catatan']
                : $pengajuan->catatan,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Jika diterima, buat akun peserta
        |--------------------------------------------------------------------------
        */

        if ($status === 'Diterima') {

            // Ketua
            $this->createPesertaAccount(
                $pengajuan->nama_ketua,
                $pengajuan->email_ketua,
                $pengajuan->kode_pengajuan
            );

            // Anggota
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
        | Activity Log Pengajuan
        |--------------------------------------------------------------------------
        */

        ActivityLogger::log(
            'Pengajuan Magang',
            'UPDATE',
            'Mengubah status pengajuan magang',
            $oldData,
            $pengajuan->fresh()->toArray()
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Status pengajuan berhasil diperbarui.',
            'data' => $pengajuan->fresh(),
        ]);
    }

    protected function createPesertaAccount($nama, $email, $passwordAwal): void
    {
        $role = Role::firstOrCreate([
            'name' => 'Peserta'
        ]);

        $user = User::where('email', $email)->first();

        /*
        |--------------------------------------------------------------------------
        | Buat akun peserta baru
        |--------------------------------------------------------------------------
        */

        if (! $user) {

            $user = User::create([
                'name' => $nama,
                'email' => $email,
                'password' => Hash::make($passwordAwal),
                'role_id' => $role->id,
                'must_change_password' => true,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Activity Log CREATE akun peserta
            |--------------------------------------------------------------------------
            */

            ActivityLogger::log(
                'Peserta',
                'CREATE',
                'Membuat akun peserta dari pengajuan magang',
                null,
                $user->fresh()->toArray()
            );

        } else {

            /*
            |--------------------------------------------------------------------------
            | Simpan data lama akun peserta
            |--------------------------------------------------------------------------
            */

            $oldUserData = $user->toArray();

            /*
            |--------------------------------------------------------------------------
            | Update akun peserta
            |--------------------------------------------------------------------------
            */

            $user->update([
                'name' => $nama,
                'role_id' => $role->id,
                'password' => Hash::make($passwordAwal),
                'must_change_password' => true,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Activity Log UPDATE akun peserta
            |--------------------------------------------------------------------------
            */

            ActivityLogger::log(
                'Peserta',
                'UPDATE',
                'Memperbarui akun peserta dari pengajuan magang',
                $oldUserData,
                $user->fresh()->toArray()
            );
        }
    }
}
