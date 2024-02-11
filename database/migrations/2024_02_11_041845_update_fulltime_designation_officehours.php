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
        Schema::table('designations', function (Blueprint $table) {

            //14 is for fulltime
            $category_fulltime = DB::table('categories')->where('category','FullTime Employees')->value('id');
            $designation_ids =DB::table('employees')->where('categories_id',$category_fulltime)->pluck('designation_id');
             DB::table('designations')->wherein('id', $designation_ids ) ->update(array( 'normal_office_hours'=> 6));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('designations', function (Blueprint $table) {
            //
        });
    }
};
