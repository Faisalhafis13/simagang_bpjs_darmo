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
            'status' => ['required', 'in:menunggu,Menunggu,pending,Pending,diterima,Diterima,accepted,Accepted,ditolak,Ditolak,rejected,Rejected'],
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

        $pengajuan = PengajuanMagang::with('anggota')->findOrFail($id);

        $oldData = $pengajuan->toArray();

        $pengajuan->update([
            'status' => $status,
            'catatan' => $data['catatan'] ?? $pengajuan->catatan,
        ]);

        // Jika diterima, buat akun ketua dan seluruh anggota
        if ($status === 'Diterima') {

            // Ketua
            $this->createPesertaAccount(
                $pengajuan->nama_ketua,
                $pengajuan->email_ketua,
                $pengajuan->kode_pengajuan
            );

            // Semua anggota
            foreach ($pengajuan->anggota as $anggota) {

                // Lewati jika email kosong
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
            'data' => $pengajuan,
        ]);
    }

    /**
     * Membuat atau memperbarui akun peserta
     */
    protected function createPesertaAccount($nama, $email, $passwordAwal): void
    {
        $role = Role::firstOrCreate([
            'name' => 'Peserta'
        ]);

        $user = User::where('email', $email)->first();

        if (! $user) {

            User::create([
                'name' => $nama,
                'email' => $email,
                'password' => Hash::make($passwordAwal),
                'role_id' => $role->id,
                'must_change_password' => true,
            ]);

        } else {

            $user->update([
                'name' => $nama,
                'role_id' => $role->id,
                'password' => Hash::make($passwordAwal),
                'must_change_password' => true,
            ]);

        }
    }
}