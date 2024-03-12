<?php

namespace Database\Seeders;

use App\User;
use Illuminate\Database\Seeder;

class RoleUserTableSeeder extends Seeder
{
    public function run()
    {
        for ($i=1; $i <= 9 ; $i++) { 
            $users = User::where('role_id',$i)->get();
            
            foreach ($users as $user) {
                if($user) $user->roles()->sync($i);
            }
        }
     
    }
}