<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToSectionEmployeesTable extends Migration
{
    public function up()
    {
        Schema::table('section_employees', function (Blueprint $table) {
            $table->unsignedBigInteger('section_or_offfice_id')->nullable();
            $table->foreign('section_or_offfice_id', 'section_or_offfice_fk_9583400')->references('id')->on('sections');
            $table->unsignedInteger('employee_id')->nullable();
            $table->foreign('employee_id', 'employee_fk_9582908')->references('id')->on('employees');
        });
    }
}
