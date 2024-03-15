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
        Schema::table('govt_calendars', function (Blueprint $table) {

            if (!Schema::hasColumn('govt_calendars', 'date')) {
                $table->date('date')->unique();
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
            } else {
                $table->unique('date');
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
        Schema::table('govt_calendars', function (Blueprint $table) {
            //
        });
    }
};
