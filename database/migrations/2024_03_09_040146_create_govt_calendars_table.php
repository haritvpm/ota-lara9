<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('govt_calendars', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date')->nullable();
            $table->integer('govtholidaystatus')->nullable();
            $table->integer('restrictedholidaystatus')->nullable();
            $table->integer('bankholidaystatus')->nullable();
            $table->longText('festivallist')->nullable();
            $table->integer('success_attendance_fetched')->nullable();
            $table->datetime('success_attendance_lastfetchtime')->nullable();
            $table->integer('success_attendance_rows_fetched')->nullable();
            $table->integer('attendance_today_trace_fetched')->nullable();
            $table->datetime('attendancetodaytrace_lastfetchtime')->nullable();
            $table->integer('attendance_today_trace_rows_fetched')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('govt_calendars');
    }
};
