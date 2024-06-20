<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToEmployeesTable extends Migration
{
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->integer('shift_time_id')->unsigned()->nullable();
            $table->foreign('shift_time_id', 'shift_time_fk_9884855')->references('id')->on('shift_times');
            
            $table->boolean('is_shift')->default(0)->nullable();
        });
    }
}
