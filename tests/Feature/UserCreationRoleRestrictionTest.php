<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserCreationRoleRestrictionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles
        Role::create(['name' => 'owner', 'description' => 'System Owner']);
        Role::create(['name' => 'manager', 'description' => 'Team Manager']);
        Role::create(['name' => 'counselor', 'description' => 'Education Counselor']);
    }

    /**
     * Test that Owner can create Manager accounts
     */
    public function test_owner_can_create_manager(): void
    {
        $owner = User::factory()->create();
        $owner->roles()->attach(Role::where('name', 'owner')->first());

        $response = $this->actingAs($owner)->post('/users', [
            'name' => 'Test Manager',
            'username' => 'testmanager',
            'email' => 'manager@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'manager',
        ]);

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('users', ['username' => 'testmanager']);
    }

    /**
     * Test that Owner can create Counselor accounts
     */
    public function test_owner_can_create_counselor(): void
    {
        $owner = User::factory()->create();
        $owner->roles()->attach(Role::where('name', 'owner')->first());

        $response = $this->actingAs($owner)->post('/users', [
            'name' => 'Test Counselor',
            'username' => 'testcounselor',
            'email' => 'counselor@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'counselor',
        ]);

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('users', ['username' => 'testcounselor']);
    }

    /**
     * Test that Owner cannot create another Owner (only 1 Owner allowed)
     */
    public function test_owner_cannot_create_another_owner(): void
    {
        $owner = User::factory()->create();
        $owner->roles()->attach(Role::where('name', 'owner')->first());

        $response = $this->actingAs($owner)->post('/users', [
            'name' => 'Another Owner',
            'username' => 'anotherowner',
            'email' => 'owner2@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'owner',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'There can only be one Owner in the system.');
        $this->assertDatabaseMissing('users', ['username' => 'anotherowner']);
    }

    /**
     * Test that Manager can create Counselor accounts
     */
    public function test_manager_can_create_counselor(): void
    {
        $manager = User::factory()->create();
        $manager->roles()->attach(Role::where('name', 'manager')->first());

        $response = $this->actingAs($manager)->post('/users', [
            'name' => 'Test Counselor',
            'username' => 'testcounselor',
            'email' => 'counselor@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'counselor',
        ]);

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('users', ['username' => 'testcounselor']);
    }

    /**
     * Test that Manager cannot create Manager accounts
     */
    public function test_manager_cannot_create_manager(): void
    {
        $manager = User::factory()->create();
        $manager->roles()->attach(Role::where('name', 'manager')->first());

        $response = $this->actingAs($manager)->post('/users', [
            'name' => 'Another Manager',
            'username' => 'anothermanager',
            'email' => 'manager2@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'manager',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Managers can only create Counselor accounts.');
        $this->assertDatabaseMissing('users', ['username' => 'anothermanager']);
    }

    /**
     * Test that Manager cannot create Owner accounts
     */
    public function test_manager_cannot_create_owner(): void
    {
        $manager = User::factory()->create();
        $manager->roles()->attach(Role::where('name', 'manager')->first());

        $response = $this->actingAs($manager)->post('/users', [
            'name' => 'Test Owner',
            'username' => 'testowner',
            'email' => 'owner@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'owner',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Managers can only create Counselor accounts.');
        $this->assertDatabaseMissing('users', ['username' => 'testowner']);
    }

    /**
     * Test that Counselor cannot create any accounts (middleware blocks them)
     */
    public function test_counselor_cannot_create_accounts(): void
    {
        $counselor = User::factory()->create();
        $counselor->roles()->attach(Role::where('name', 'counselor')->first());

        $response = $this->actingAs($counselor)->post('/users', [
            'name' => 'Test User',
            'username' => 'testuser',
            'email' => 'user@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'counselor',
        ]);

        // Middleware should block access with 403 Forbidden
        $response->assertStatus(403);
        $this->assertDatabaseMissing('users', ['username' => 'testuser']);
    }

    /**
     * Test that Counselor cannot access user management index page
     */
    public function test_counselor_cannot_access_users_index(): void
    {
        $counselor = User::factory()->create();
        $counselor->roles()->attach(Role::where('name', 'counselor')->first());

        $response = $this->actingAs($counselor)->get('/users');

        $response->assertStatus(403);
    }

    /**
     * Test that Owner can access user management index page
     */
    public function test_owner_can_access_users_index(): void
    {
        $owner = User::factory()->create();
        $owner->roles()->attach(Role::where('name', 'owner')->first());

        $response = $this->actingAs($owner)->get('/users');

        $response->assertStatus(200);
    }

    /**
     * Test that Manager can access user management index page
     */
    public function test_manager_can_access_users_index(): void
    {
        $manager = User::factory()->create();
        $manager->roles()->attach(Role::where('name', 'manager')->first());

        $response = $this->actingAs($manager)->get('/users');

        $response->assertStatus(200);
    }
}
