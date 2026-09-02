<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminUserSeeder extends Seeder
{
    /**
     * Seed the default Super Admin role and admin account.
     *
     * Admin login URL: /admin
     * Default credentials (local dev):
     *   Email:    admin@swapcircle.com
     *   Password: admin
     */
    public function run(): void
    {
        DB::table('users_system_roles')->updateOrInsert(
            ['users_system_roles_id' => 1],
            [
                'name' => 'Super Admin',
                'status' => 'Active',
            ]
        );

        DB::table('users_system')->updateOrInsert(
            ['email' => 'admin@swapcircle.com'],
            [
                'users_system_roles_id' => 1,
                'first_name' => 'Super Admin',
                'password' => 'admin',
                'mobile' => '+2349134448800',
                'city' => 'London',
                'address' => 'England',
                'user_image' => 'uploads/users_system/user-677d9d74c67929023eedb8469a34003b.jpeg',
                'is_deleted' => 'No',
                'status' => 'Active',
            ]
        );
    }
}
