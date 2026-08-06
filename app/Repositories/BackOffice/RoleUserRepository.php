<?php

namespace App\Repositories\BackOffice;

use App\Models\User;
use App\Models\Role;
use App\Helpers\ActivityLogger;

class RoleUserRepository
{
    public function index()
    {
        $roles = Role::orderBy('name')->get();

        return view('back-office.role-user.index', compact('roles'));
    }

    public function getData()
    {
        $users = User::with('role')
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $users,
        ]);
    }

    public function show($id)
    {
        $user = User::with('role')->findOrFail($id);

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

        // Simpan data lama
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

        // Simpan log
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

        // Simpan data sebelum dihapus
        $oldData = $user->toArray();

        $user->delete();

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