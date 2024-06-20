<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShiftTimesTable extends Migration
{
    public function up()
    {
        Schema::create('shift_times', function (Blueprint $table) {
            $table->increments('id');
            $table->string('groupname')->unique();
            $table->integer('shift_minutes');
            $table->integer('minutes_for_ot')->nullable();
            $table->timestamps();
         //   $table->softDeletes();
        });
    }
}
