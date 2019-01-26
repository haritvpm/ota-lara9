<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Add59b172c3aacedRelationshipsToEmployeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employees', function(Blueprint $table) {
            if (!Schema::hasColumn('employees', 'designation_id')) {
                $table->integer('designation_id')->unsigned()->nullable();
                $table->foreign('designation_id', '71926_59b172c2c1dc1')->references('id')->on('designations')->onDelete('cascade');
                }
                
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employees', function(Blueprint $table) {
            
        });
    }
}
