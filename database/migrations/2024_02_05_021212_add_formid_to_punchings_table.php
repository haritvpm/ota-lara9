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
        Schema::table('punchings', function (Blueprint $table) {
            $table->integer('form_id')->unsigned()->nullable();
            $table->foreign('form_id', 'form_fk_9464960')->references('id')->on('punching_forms');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('punchings', function (Blueprint $table) {
            //
        });
    }
};
