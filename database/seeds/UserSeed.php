<?php

use Illuminate\Database\Seeder;

class UserSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            
            ['id' => 1, 'name' => 'Admin', 'email' => 'admin@admin.com', 'password' => '$2y$10$7r2K1gE4LG.hgiABm26REuDrh4.2GxkxXXyiW4.3SbuGo3Mwdda9m', 'role_id' => 1, 'remember_token' => '', 'username' => 'admin',],
            ['id' => 2, 'name' => 'Accounts D', 'email' => 'admin@admin.com', 'password' => '$2y$10$7r2K1gE4LG.hgiABm26REuDrh4.2GxkxXXyiW4.3SbuGo3Mwdda9m', 'role_id' => 2, 'remember_token' => '', 'username' => 'sn.accd',],

        ];

        foreach ($items as $item) {
            \App\User::create($item);
        }
    }
}
