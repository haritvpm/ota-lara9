<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            PermissionsTableSeeder::class,
            RoleSeed::class,
            PermissionRoleTableSeeder::class,
            UserSeed::class,
            RoleUserTableSeeder::class,
        ]);
    }
}

