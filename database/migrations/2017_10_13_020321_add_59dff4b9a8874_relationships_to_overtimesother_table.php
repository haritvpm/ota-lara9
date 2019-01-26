<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Add59dff4b9a8874RelationshipsToOvertimesOtherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('overtime_others', function(Blueprint $table) {
            if (!Schema::hasColumn('overtime_others', 'form_id')) {
                $table->integer('form_id')->unsigned()->nullable();
                $table->foreign('form_id', '81370_59dff4b8ab0c4')->references('id')->on('form_others')->onDelete('cascade');
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
        Schema::table('overtime_others', function(Blueprint $table) {
            
        });
    }
}
