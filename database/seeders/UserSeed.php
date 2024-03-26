<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\User;

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
            ['name' => 'IT Admin', 'email' => 'itadmin@admin.com', 'password' => '$2y$10$7r2K1gE4LG.hgiABm26REuDrh4.2GxkxXXyiW4.3SbuGo3Mwdda9m', 'role_id' => 8, 'remember_token' => '', 'username' => 'itadmin',],
            ['name' => 'SuperAdmin', 'email' => 'superadmin@admin.com', 'password' => '$2y$10$7r2K1gE4LG.hgiABm26REuDrh4.2GxkxXXyiW4.3SbuGo3Mwdda9m', 'role_id' => 8, 'remember_token' => '', 'username' => 'superadmin',],

        ];

          //  \App\User::create($item);
          foreach ($items as $item) {
            if(!User::where('name', $item['name'])->first()){
                User::create($item);
               
            }
        }
    }
}
