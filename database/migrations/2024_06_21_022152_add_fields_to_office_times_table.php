<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('office_times', function (Blueprint $table) {
            $table->integer('minutes_for_ot_workingday')->nullable()->change();
            $table->integer('minutes_for_ot_holiday')->nullable()->change();;
            $table->integer('sittingday_duration_min_for_second_ot')->nullable();
            $table->integer('workingday_duration_min_for_first_ot')->nullable();
            $table->string('sitting_ot_time_str')->nullable();
            $table->integer('sitting_ot_initial_leeway_min')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('office_times', function (Blueprint $table) {
            //
        });
    }
};
