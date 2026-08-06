<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\MenuGroup;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $group = MenuGroup::firstOrCreate(['name' => 'Back Office']);

        $menus = [
            ['name' => 'Dashboard', 'route' => 'back-office.dashboard'],
            ['name' => 'Data Pengajuan', 'route' => 'back-office.pengajuan'],
            ['name' => 'Data Peserta', 'route' => 'back-office.peserta'],
            ['name' => 'Data Perguruan Tinggi', 'route' => 'back-office.perguruan-tinggi'],
            ['name' => 'Monitoring Logbook', 'route' => 'back-office.logbook'],
            ['name' => 'Log History', 'route' => 'back-office.history'],
            ['name' => 'Data Mentor', 'route' => 'back-office.mentor'],
            ['name' => 'Role Menu', 'route' => 'back-office.role-menu'],
            ['name' => 'Role User', 'route' => 'back-office.role-user'],
        ];

        foreach ($menus as $menuData) {
            Menu::firstOrCreate([
                'group_id' => $group->id,
                'route' => $menuData['route'],
            ], [
                'name' => $menuData['name'],
            ]);
        }
    }
}
