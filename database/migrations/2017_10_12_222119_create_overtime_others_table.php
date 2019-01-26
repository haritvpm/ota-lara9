<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOvertimeOthersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('overtime_others', function (Blueprint $table) {
            $table->increments('id');
             $table->string('pen');
                $table->string('designation');
                $table->string('from');
                $table->string('to');
                $table->integer('count')->nullable();
                $table->integer('rate')->nullable();
                $table->string('worknature')->nullable();
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
        Schema::dropIfExists('overtime_others');
    }
}
