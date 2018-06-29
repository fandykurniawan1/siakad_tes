<?php

use Webpatser\Uuid\Uuid;
use Illuminate\Database\Seeder;
use App\Models\UserManagement\Role;
use App\Models\UserManagement\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::whereScope('Admin Backend')->delete();

        Permission::insert([
            ['id' => Uuid::generate()->string, 'scope' => 'Admin Backend', 'group' => 'Setting', 'name' => 'change-preference', 'display_name' => 'Change Preference'],

            ['id' => Uuid::generate()->string, 'scope' => 'Admin Backend', 'group' => 'User', 'name' => 'view-user-list', 'display_name' => 'View User List'],
            ['id' => Uuid::generate()->string, 'scope' => 'Admin Backend', 'group' => 'User', 'name' => 'create-user', 'display_name' => 'Create User'],
            ['id' => Uuid::generate()->string, 'scope' => 'Admin Backend', 'group' => 'User', 'name' => 'edit-user', 'display_name' => 'Edit User'],
            ['id' => Uuid::generate()->string, 'scope' => 'Admin Backend', 'group' => 'User', 'name' => 'delete-user', 'display_name' => 'Delete User'],
            ['id' => Uuid::generate()->string, 'scope' => 'Admin Backend', 'group' => 'User', 'name' => 'view-user-login-history', 'display_name' => 'View User Login History'],
            ['id' => Uuid::generate()->string, 'scope' => 'Admin Backend', 'group' => 'User', 'name' => 'change-user-password', 'display_name' => 'Change User Password'],

            ['id' => Uuid::generate()->string, 'scope' => 'Admin Backend', 'group' => 'Role', 'name' => 'view-role-list', 'display_name' => 'View Role List'],
            ['id' => Uuid::generate()->string, 'scope' => 'Admin Backend', 'group' => 'Role', 'name' => 'create-role', 'display_name' => 'Create Role'],
            ['id' => Uuid::generate()->string, 'scope' => 'Admin Backend', 'group' => 'Role', 'name' => 'edit-role', 'display_name' => 'Edit Role'],
            ['id' => Uuid::generate()->string, 'scope' => 'Admin Backend', 'group' => 'Role', 'name' => 'delete-role', 'display_name' => 'Delete Role'],

            ['id' => Uuid::generate()->string, 'scope' => 'Admin Backend', 'group' => 'Master Data', 'name' => 'view-master-data-list', 'display_name' => 'View Master Data List'],
            ['id' => Uuid::generate()->string, 'scope' => 'Admin Backend', 'group' => 'Master Data', 'name' => 'create-master-data', 'display_name' => 'Create Master Data'],
            ['id' => Uuid::generate()->string, 'scope' => 'Admin Backend', 'group' => 'Master Data', 'name' => 'edit-master-data', 'display_name' => 'Edit Master Data'],
            ['id' => Uuid::generate()->string, 'scope' => 'Admin Backend', 'group' => 'Master Data', 'name' => 'delete-master-data', 'display_name' => 'Delete Master Data'],

            ['id' => Uuid::generate()->string, 'scope' => 'Admin Backend', 'group' => 'Merchant', 'name' => 'view-merchant-list', 'display_name' => 'View Merchant List'],
            ['id' => Uuid::generate()->string, 'scope' => 'Admin Backend', 'group' => 'Merchant', 'name' => 'create-merchant', 'display_name' => 'Create Merchant'],
            ['id' => Uuid::generate()->string, 'scope' => 'Admin Backend', 'group' => 'Merchant', 'name' => 'edit-merchant', 'display_name' => 'Edit Merchant'],
            ['id' => Uuid::generate()->string, 'scope' => 'Admin Backend', 'group' => 'Merchant', 'name' => 'delete-merchant', 'display_name' => 'Delete Merchant'],

            ['id' => Uuid::generate()->string, 'scope' => 'Admin Backend', 'group' => 'Banner', 'name' => 'view-banner-list', 'display_name' => 'View Banner List'],
            ['id' => Uuid::generate()->string, 'scope' => 'Admin Backend', 'group' => 'Banner', 'name' => 'create-banner', 'display_name' => 'Create Banner'],
            ['id' => Uuid::generate()->string, 'scope' => 'Admin Backend', 'group' => 'Banner', 'name' => 'edit-banner', 'display_name' => 'Edit Banner'],
            ['id' => Uuid::generate()->string, 'scope' => 'Admin Backend', 'group' => 'Banner', 'name' => 'delete-banner', 'display_name' => 'Delete Banner'],

            /* Other Permissions */
        ]);

        if ($role = Role::first()) $role->attachPermissions(Permission::whereScope('Admin Backend')->get());
    }
}
