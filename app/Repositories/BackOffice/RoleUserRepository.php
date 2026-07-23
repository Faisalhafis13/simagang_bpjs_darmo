<?php

namespace App\Repositories\BackOffice;

use App\Models\User;
use App\Models\Role;

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
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role_id' => $request->role_id,
        ]);

        return response()->json([
            'message' => 'User berhasil ditambahkan.'
        ]);
    }

    public function update($request, $id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
        ]);

        if ($request->filled('password')) {

            $user->password = bcrypt($request->password);

            $user->save();

        }

        return response()->json([
            'message' => 'User berhasil diubah.'
        ]);
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();

        return response()->json([
            'message' => 'User berhasil dihapus.'
        ]);
    }
}