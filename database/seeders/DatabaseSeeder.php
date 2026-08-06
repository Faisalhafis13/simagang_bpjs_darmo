<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seeder
        $this->call([
            RoleSeeder::class,
            MenuGroupSeeder::class,
            MenuSeeder::class,
            RoleMenuSeeder::class,
        ]);

        $adminRole = Role::where('name', 'Admin')->first();

        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role_id' => $adminRole ? $adminRole->id : null,
        ]);
    }
}
