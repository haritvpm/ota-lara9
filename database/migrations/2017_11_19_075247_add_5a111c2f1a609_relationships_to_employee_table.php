<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Add5a111c2f1a609RelationshipsToEmployeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employees', function(Blueprint $table) {
           
                if (!Schema::hasColumn('employees', 'categories_id')) {
                $table->integer('categories_id')->unsigned()->nullable();
                $table->foreign('categories_id', '71926_5a111c2e05979')->references('id')->on('categories')->onDelete('cascade');
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
        Schema::table('employees', function(Blueprint $table) {
            
        });
    }
}
