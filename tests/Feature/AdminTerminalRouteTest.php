<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminTerminalRouteTest extends TestCase
{
    use RefreshDatabase;

    // Note: Not using RefreshDatabase as we want to test against the real DB state 
    // or we'd need to mock roles. For safety, we'll try to use existing users or mocks.
    // Actually, let's just mock the user auth.

    public function test_admin_can_access_terminals_manage_page()
    {
        // Mock an Admin User (Role 1)
        $admin = new User();
        $admin->id = 1;
        $admin->role_id = 1;
        $admin->statut = 1; // Is_Active middleware checks 'statut' usually
        
        $this->actingAs($admin)
             ->get('/admin/terminals')
             ->assertStatus(200)
             ->assertViewIs('admin.terminals.index');
    }

    public function test_non_admin_cannot_access_terminals_manage_page()
    {
        // Mock a Regular User (Role 2)
        $user = new User();
        $user->id = 2;
        $user->role_id = 2;
        $user->statut = 1;

        $this->actingAs($user)
             ->get('/admin/terminals')
             ->assertStatus(403);
    }

    public function test_guest_cannot_access_terminals_manage_page()
    {
        $this->get('/admin/terminals')
             ->assertStatus(302)
             ->assertRedirect('/login');
    }
}
