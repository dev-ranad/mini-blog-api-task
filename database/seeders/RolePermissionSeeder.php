<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        try {
            DB::beginTransaction();
            $permissions = [
                'post.index',
                'post.show',
                'post.store',
                'post.update',
                'post.destroy',
                'comment.index',
                'comment.status',
                'comment.show',
                'comment.store',
                'comment.update',
                'comment.destroy',
                'category.index',
                'category.show',
                'category.store',
                'category.destroy',
                'user.index',
            ];

            foreach ($permissions as $permission) {
                Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'api']);
            }


            $superAdmin = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'api']);
            $admin = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'api']);
            $moderator = Role::firstOrCreate(['name' => 'Moderator', 'guard_name' => 'api']);
            $user = Role::firstOrCreate(['name' => 'User', 'guard_name' => 'api']);


            $superAdmin->givePermissionTo(Permission::all());

            $admin->givePermissionTo([
                'post.index',
                'post.show',
                'post.update',
                'comment.index',
                'comment.show',
                'comment.destroy',
                'category.index',
                'category.show',
                'category.store',
                'category.destroy',
            ]);

            $moderator->givePermissionTo([
                'post.index',
                'post.show',
                'comment.index',
                'comment.status',
                'comment.show',
                'category.index',
            ]);

            $user->givePermissionTo([
                'category.index',
                'post.index',
                'post.show',
                'post.store',
                'comment.store',
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            // Handle the exception if needed
            logger()->error('Error truncating permissions or roles: ' . $e->getMessage());
        }

    }
}
