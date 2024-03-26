<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Role;

class RoleSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            
            ['id' => 1, 'title' => 'Administrator (can create other users)',],
            ['id' => 2, 'title' => 'Simple user',],
            ['id' => 3, 'title' => 'other department',],
            ['id' => 4, 'title' => 'office of secretary',],
            ['id' => 8, 'title' => 'ITAdmin',],
            ['id' => 9, 'title' => 'SuperAdmin',],

        ];

        foreach ($items as $item) {
            if(!Role::where('id', $item['id'])->first()){
                 Role::create($item);
               
            }
       
        }
    }
}
