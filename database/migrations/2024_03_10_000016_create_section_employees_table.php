<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSectionEmployeesTable extends Migration
{
    public function up()
    {
        Schema::create('section_employees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date_from')->nullable();
            $table->date('date_to')->nullable();
            $table->timestamps();
        });
    }
}
