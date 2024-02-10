<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
           
          if(!Schema::hasColumn( 'categories','punching')){
          // $table->unique('category'); 
           $table->boolean('punching')->default(1)->nullable();
          }
        });

        
        //rename 'Personal Staff', 'PA to MLAs'
        DB::table('categories')->where('category','Personal Staff')->update(array(
            'category'=>'Personal Staff - Gazetted', 'punching' => 0));
        DB::table('categories')->where('category','PA to MLAs')->update(array(
                'category'=>'PA to MLAs - Gazetted', 'punching' => 0));
        
      //  DB::table('categories')->where('category','PartTime Employees')->update(array( 'normal_office_hours'=> 3));
        
        //DB::table('categories')->where('category','FullTime Employees')->update(array('normal_office_hours'=> 6));

        
        DB::table('categories')->where('category','Watch and Ward')->update(array('punching' => 0));
        if(DB::table('categories')->count() == 18){
            DB::table('categories')->insert([
                ['category' => 'PA to MLAs - Non-Gazetted', 'punching' => 0],
                ['category' => 'Personal Staff - Comm Chairman', 'punching' => 0],
                ['category' => 'Personal Staff - Non-Gazetted', 'punching' => 0],
                ['category' => 'Relieved/Retired', 'punching' => 0],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('normal_office_hours');
        });
    }
};
