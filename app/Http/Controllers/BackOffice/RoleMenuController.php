<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Role;
use App\Models\RoleMenu;
use Illuminate\Http\Request;

class RoleMenuController extends Controller
{
    public function index()
    {
        $roles = Role::orderBy('name')->get();
        $menus = Menu::with('group')->orderBy('name')->get();
        $roleMenus = RoleMenu::with(['role','menu'])->orderBy('id')->get();

        return view('back-office.role-menu.index', compact('roles', 'menus', 'roleMenus'));
    }

    public function apiIndex(Request $request)
    {
        $perPage = $request->integer('per_page', 10);
        $query = RoleMenu::with(['role', 'menu'])->orderBy('id');

        if ($request->filled('role_id')) {
            $query->where('role_id', $request->role_id);
        }

        if ($request->filled('menu_id')) {
            $query->where('menu_id', $request->menu_id);
        }

        $roleMenus = $query->paginate($perPage)->withQueryString();

        return response()->json([
            'status' => 'success',
            'data' => $roleMenus->items(),
            'meta' => [
                'current_page' => $roleMenus->currentPage(),
                'per_page' => $roleMenus->perPage(),
                'total' => $roleMenus->total(),
                'last_page' => $roleMenus->lastPage(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'role_id' => ['required', 'exists:roles,id'],
            'menu_id' => ['required', 'exists:menus,id'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $roleMenu = RoleMenu::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Role menu berhasil dibuat.',
            'data' => $roleMenu->load(['role', 'menu']),
        ]);
    }

    public function update(Request $request, RoleMenu $roleMenu)
    {
        $data = $request->validate([
            'role_id' => ['required', 'exists:roles,id'],
            'menu_id' => ['required', 'exists:menus,id'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $roleMenu->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Role menu berhasil diperbarui.',
            'data' => $roleMenu->load(['role', 'menu']),
        ]);
    }

    public function destroy(RoleMenu $roleMenu)
    {
        $roleMenu->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Role menu berhasil dihapus.',
        ]);
    }
}
