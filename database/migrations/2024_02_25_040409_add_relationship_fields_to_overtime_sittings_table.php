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
  
        Schema::table('overtime_sittings', function (Blueprint $table) {
            $table->integer('overtime_id')->unsigned()->nullable();
            $table->foreign('overtime_id', 'overtime_fk_9533396')->references('id')->on('overtimes')->onDelete('cascade');;
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('overtime_sittings', function (Blueprint $table) {
            $table->dropForeign('overtime_fk_9533396');

            $table->dropColumn('overtime_id');
        });
    }
};
