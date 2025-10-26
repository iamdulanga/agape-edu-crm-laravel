<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Roles
        $roles = [
            ['name' => 'owner', 'description' => 'System Owner'],
            ['name' => 'manager', 'description' => 'Team Manager'],
            ['name' => 'counselor', 'description' => 'Education Counselor'],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }

        // Create Test Users
        $users = [
            [
                'name' => 'System Owner',
                'username' => 'owner',
                'email' => 'owner@agape.edu',
                'password' => Hash::make('password'),
                'roles' => ['owner'],
            ],
            [
                'name' => 'Team Manager',
                'username' => 'manager',
                'email' => 'manager@agape.edu',
                'password' => Hash::make('password'),
                'roles' => ['manager'],
            ],
            [
                'name' => 'Education Counselor',
                'username' => 'counselor',
                'email' => 'counselor@agape.edu',
                'password' => Hash::make('password'),
                'roles' => ['counselor'],
            ],
        ];

        foreach ($users as $userData) {
            $user = User::create([
                'name' => $userData['name'],
                'username' => $userData['username'],
                'email' => $userData['email'],
                'password' => $userData['password'],
            ]);

            // Attach roles
            foreach ($userData['roles'] as $roleName) {
                $role = Role::where('name', $roleName)->first();
                if ($role) {
                    $user->roles()->attach($role);
                }
            }
        }

        // Create sample leads
        \App\Models\Lead::factory(10)->create();
    }
}
