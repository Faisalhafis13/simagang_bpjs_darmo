<?php

namespace App\Repositories\BackOffice;

use App\Models\Menu;
use App\Models\Role;
use App\Models\RoleMenu;
use Illuminate\Http\Request;
use App\Helpers\ActivityLogger;

class RoleMenuRepository
{
    public function index()
    {
        $roles = Role::orderBy('name')->get();
        $menus = Menu::orderBy('name')->get();

        return view(
            'back-office.role-menu.index',
            compact('roles', 'menus')
        );
    }

    public function getData(Request $request)
    {
        $roles = Role::with([
            'roleMenus.menu'
        ])->orderBy('name')->get();

        $data = $roles->map(function ($role) {

            $roleMenus = $role->roleMenus;

            return [

                'id' => $role->id,

                'role' => $role->name,

                'status' => $roleMenus->contains('status', 'active')
                    ? 'active'
                    : 'inactive',

                'menus' => $roleMenus->map(function ($item) {

                    return [

                        'role_menu_id' => $item->id,

                        'menu_id' => $item->menu_id,

                        'name' => $item->menu->name,

                        'status' => $item->status,

                    ];

                })->values(),

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

            'role_id' => 'required|exists:roles,id',

            'menu_id' => 'required|exists:menus,id',

            'status' => 'required|in:active,inactive',

        ]);

        $roleMenu = RoleMenu::create($data);

        ActivityLogger::log(
            'Role Menu',
            'CREATE',
            'Menambah Hak Akses Role',
            null,
            $roleMenu->load('role', 'menu')->toArray()
        );

        return response()->json([

            'status' => 'success',

            'message' => 'Role Menu berhasil ditambahkan.',

            'data' => $roleMenu->load('role', 'menu'),

        ]);
    }

    public function update(Request $request, RoleMenu $roleMenu)
    {
        $data = $request->validate([

            'role_id' => 'required|exists:roles,id',

            'menu_id' => 'required|exists:menus,id',

            'status' => 'required|in:active,inactive',

        ]);

        $oldData = $roleMenu->load('role', 'menu')->toArray();

        $roleMenu->update($data);

        ActivityLogger::log(
            'Role Menu',
            'UPDATE',
            'Mengubah Hak Akses Role',
            $oldData,
            $roleMenu->fresh()->load('role', 'menu')->toArray()
        );

        return response()->json([

            'status' => 'success',

            'message' => 'Role Menu berhasil diupdate.',

            'data' => $roleMenu->load('role', 'menu'),

        ]);
    }

    public function destroy(RoleMenu $roleMenu)
    {
        $oldData = $roleMenu->load('role', 'menu')->toArray();

        $roleMenu->delete();

        ActivityLogger::log(
            'Role Menu',
            'DELETE',
            'Menghapus Hak Akses Role',
            $oldData,
            null
        );

        return response()->json([

            'status' => 'success',

            'message' => 'Role Menu berhasil dihapus.',

        ]);
    }
}