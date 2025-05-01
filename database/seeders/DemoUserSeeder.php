<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DemoUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('password'),
        ]);

        $superAdminRole = Role::where('name', 'Super Admin')->where('guard_name', 'api')->first();
        $superAdmin->assignRole($superAdminRole);


        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        $adminRole = Role::where('name', 'Admin')->where('guard_name', 'api')->first();
        $admin->assignRole($adminRole);


        $moderator = User::create([
            'name' => 'Moderator User',
            'email' => 'moderator@example.com',
            'password' => Hash::make('password'),
        ]);

        $moderatorRole = Role::where('name', 'Moderator')->where('guard_name', 'api')->first();
        $moderator->assignRole($moderatorRole);


        $user = User::create([
            'name' => 'Normal User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
        ]);

        $userRole = Role::where('name', 'User')->where('guard_name', 'api')->first();
        $user->assignRole($userRole);
    }
}
