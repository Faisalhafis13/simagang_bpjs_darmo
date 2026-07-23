<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleUserApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_return_a_single_user_for_editing(): void
    {
        $role = Role::create(['name' => 'Admin']);
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role_id' => $role->id,
        ]);

        $authUser = User::create([
            'name' => 'Owner',
            'email' => 'owner@example.com',
            'password' => bcrypt('password'),
            'role_id' => $role->id,
        ]);

        $response = $this->actingAs($authUser)->getJson('/api/back-office/role-user/' . $user->id);

        $response->assertOk();
        $response->assertJsonPath('id', $user->id);
        $response->assertJsonPath('name', 'Test User');
        $response->assertJsonPath('role_id', $role->id);
    }
}
