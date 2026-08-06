<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Role;
use App\Models\RoleMenu;
use Illuminate\Database\Seeder;

class RoleMenuSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'Admin')->first();

        if (! $adminRole) {
            return;
        }

        foreach (Menu::all() as $menu) {
            RoleMenu::firstOrCreate([
                'role_id' => $adminRole->id,
                'menu_id' => $menu->id,
            ], [
                'status' => 'active',
            ]);
        }
    }
}
