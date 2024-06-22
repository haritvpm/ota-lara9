<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfficeTimesTable extends Migration
{
    public function up()
    {
        Schema::create('office_times', function (Blueprint $table) {
            $table->increments('id');
            $table->string('groupname')->unique();
            $table->time('fn_from')->nullable();
            $table->time('an_to')->nullable();
            $table->integer('minutes_for_ot_workingday')->nullable();;
            $table->integer('minutes_for_ot_holiday')->nullable();;
            $table->integer('max_ot_workingday')->nullable();
            $table->integer('max_ot_sittingday')->nullable();
            $table->integer('max_ot_holiday')->nullable();
           // $table->integer('office_minutes');
            $table->timestamps();
          //  $table->softDeletes();
        });
    }
}
