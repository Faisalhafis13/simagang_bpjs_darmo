<?php

namespace Database\Seeders;

use App\Models\MenuGroup;
use Illuminate\Database\Seeder;

class MenuGroupSeeder extends Seeder
{
    public function run(): void
    {
        MenuGroup::create(['name' => 'Back Office']);
    }
}
