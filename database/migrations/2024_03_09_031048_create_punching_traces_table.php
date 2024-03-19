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
        Schema::create('punching_traces', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('aadhaarid');
            $table->string('org_emp_code')->nullable();
            $table->string('device')->nullable();
            $table->string('attendance_type')->nullable();
            $table->string('auth_status')->nullable();
            $table->string('err_code')->nullable();
            $table->date('att_date');
            $table->time('att_time');
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
        Schema::dropIfExists('punching_traces');
    }
};
