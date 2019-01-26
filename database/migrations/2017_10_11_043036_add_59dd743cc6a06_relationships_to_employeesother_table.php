<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Add59dd743cc6a06RelationshipsToEmployeesOtherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employees_others', function(Blueprint $table) {
            if (!Schema::hasColumn('employees_others', 'designation_id')) {
                $table->integer('designation_id')->unsigned()->nullable();
                $table->foreign('designation_id', '80670_59dd743bd2cdc')->references('id')->on('designations_others')->onDelete('cascade');
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
        Schema::table('employees_others', function(Blueprint $table) {
            
        });
    }
}
