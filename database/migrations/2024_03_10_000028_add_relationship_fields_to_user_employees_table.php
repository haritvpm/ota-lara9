<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToUserEmployeesTable extends Migration
{
    public function up()
    {
        Schema::table('user_employees', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->nullable();
            $table->foreign('user_id', 'user_fk_9582933')->references('id')->on('users');
            $table->unsignedInteger('employee_id')->nullable();
            $table->foreign('employee_id', 'employee_fk_9582934')->references('id')->on('employees');
        });
    }
}
