<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Add59f48fa38b78dRelationshipsToAttendanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attendances', function(Blueprint $table) {
            if (!Schema::hasColumn('attendances', 'session_id')) {
                $table->integer('session_id')->unsigned()->nullable();
                $table->foreign('session_id', '85158_59f48fa25817b')->references('id')->on('sessions')->onDelete('cascade');
                }
                if (!Schema::hasColumn('attendances', 'employee_id')) {
                $table->integer('employee_id')->unsigned()->nullable();
                $table->foreign('employee_id', '85158_59f48fa25ef36')->references('id')->on('employees');
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
        Schema::table('attendances', function(Blueprint $table) {
            
        });
    }
}
