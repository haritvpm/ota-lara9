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
        Schema::table('punching_registers', function (Blueprint $table) {
            $table->unsignedInteger('employee_id')->nullable();
            $table->foreign('employee_id', 'employee_fk_9585941')->references('id')->on('employees');
            $table->unsignedInteger('punchin_id')->nullable();
            $table->foreign('punchin_id', 'punchin_fk_9585942')->references('id')->on('punchings');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('punching_registers', function (Blueprint $table) {
            //
        });
    }
};
