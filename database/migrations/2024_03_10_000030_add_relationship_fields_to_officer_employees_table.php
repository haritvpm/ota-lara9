<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToOfficerEmployeesTable extends Migration
{
    public function up()
    {
        Schema::table('officer_employees', function (Blueprint $table) {
            $table->unsignedInteger('officer_id')->nullable();
            $table->foreign('officer_id', 'officer_fk_9584053')->references('id')->on('users');
            $table->unsignedInteger('employee_id')->nullable();
            $table->foreign('employee_id', 'employee_fk_9584057')->references('id')->on('employees');
        });
    }
}
