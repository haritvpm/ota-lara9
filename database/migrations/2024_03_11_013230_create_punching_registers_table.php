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
        Schema::create('punching_registers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date');
            $table->string('duration')->nullable();
            $table->string('flexi')->nullable();
            $table->string('grace_min')->nullable();
            $table->string('extra_min')->nullable();
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
        //
    }
};
