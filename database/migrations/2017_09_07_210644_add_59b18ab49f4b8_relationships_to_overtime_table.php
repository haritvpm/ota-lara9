<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Add59b18ab49f4b8RelationshipsToOvertimeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('overtimes', function(Blueprint $table) {
            if (!Schema::hasColumn('overtimes', 'form_id')) {
                $table->integer('form_id')->unsigned()->nullable();
                $table->foreign('form_id', '71950_59b18ab39f6ab')->references('id')->on('forms')->onDelete('cascade');
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
        Schema::table('overtimes', function(Blueprint $table) {
            
        });
    }
}
