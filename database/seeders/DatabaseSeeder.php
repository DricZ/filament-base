<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $service = app(\App\Services\PermissionService::class);
        $service->syncAll();

        // Buat role untuk kedua guard
        $guards = ['web', 'api'];

        foreach ($guards as $guard) {
            $role = Role::firstOrCreate([
                'name' => 'Super Admin',
                'guard_name' => $guard
            ]);

            $permissions = Permission::where('guard_name', $guard)->get();
            $role->syncPermissions($permissions);
        }

        // Buat user untuk kedua guard
        $users = [
            [
                'name' => 'Web Admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('admin'),
                'guard' => 'web'
            ],
            [
                'name' => 'API Admin',
                'email' => 'api@example.com',
                'password' => bcrypt('api'),
                'guard' => 'api'
            ]
        ];

        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => $userData['password'],
                    'email_verified_at' => now()
                ]
            );

            $role = Role::where('name', 'Super Admin')
                ->where('guard_name', $userData['guard'])
                ->first();

            $user->assignRole($role);
        }
    }
}