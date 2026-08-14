<?php

namespace App\Repositories\BackOffice;

use App\Models\User;
use App\Models\Role;
use App\Helpers\ActivityLogger;
use App\Models\PengajuanMagang;

class RoleUserRepository
{
    public function index()
    {
        $roles = Role::orderBy('name')->get();

        ActivityLogger::log(
            'Role User',
            'VIEW',
            'Membuka halaman manajemen user',
            null,
            null
        );

        return view(
            'back-office.role-user.index',
            compact('roles')
        );
    }

public function getData()
{
    $users = User::with('role')
        ->where(function ($query) {

            // User yang bukan peserta tetap ditampilkan
            $query->where('role_id', '!=', 2)

                // Peserta hanya ditampilkan jika memiliki
                // pengajuan yang masih aktif
                ->orWhere(function ($q) {

                    $q->where('role_id', 2)
                        ->where(function ($userQuery) {

                            // Peserta sebagai ketua
                            $userQuery->whereHas('pengajuanKetua', function ($pengajuanQuery) {
                                $pengajuanQuery
                                    ->where('status', 'Diterima')
                                    ->whereNull('archived_at');
                            })

                            // Peserta sebagai anggota
                            ->orWhereHas('anggotaMagang', function ($anggotaQuery) {
                                $anggotaQuery->whereHas('pengajuan', function ($pengajuanQuery) {
                                    $pengajuanQuery
                                        ->where('status', 'Diterima')
                                        ->whereNull('archived_at');
                                });
                            });
                        });
                });
        })
        ->latest()
        ->get();

    return response()->json([
        'status' => 'success',
        'data' => $users,
    ]);
}
    public function show($id)
    {
        $user = User::with('role')
            ->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $user,
        ]);
    }

    public function store($request)
    {
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'role_id'  => $request->role_id,
        ]);

        ActivityLogger::log(
            'Role User',
            'CREATE',
            'Menambah User',
            null,
            $user->toArray()
        );

        return response()->json([
            'message' => 'User berhasil ditambahkan.'
        ]);
    }

    public function update($request, $id)
    {
        $user = User::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Simpan data lama
        |--------------------------------------------------------------------------
        */

        $oldData = $user->toArray();

        $user->update([
            'name'    => $request->name,
            'email'   => $request->email,
            'role_id' => $request->role_id,
        ]);

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
            $user->save();
        }

        /*
        |--------------------------------------------------------------------------
        | Activity Log
        |--------------------------------------------------------------------------
        */

        ActivityLogger::log(
            'Role User',
            'UPDATE',
            'Mengubah User',
            $oldData,
            $user->fresh()->toArray()
        );

        return response()->json([
            'message' => 'User berhasil diubah.'
        ]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Simpan data sebelum dihapus
        |--------------------------------------------------------------------------
        */

        $oldData = $user->toArray();

        $user->delete();

        /*
        |--------------------------------------------------------------------------
        | Activity Log
        |--------------------------------------------------------------------------
        */

        ActivityLogger::log(
            'Role User',
            'DELETE',
            'Menghapus User',
            $oldData,
            null
        );

        return response()->json([
            'message' => 'User berhasil dihapus.'
        ]);
    }
}
