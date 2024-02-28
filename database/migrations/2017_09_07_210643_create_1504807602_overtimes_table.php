<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Create1504807602OvertimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {//https://github.com/AXN-Informatique/laravel-pk-int-to-bigint
        if(! Schema::hasTable('overtimes')) {
            Schema::create('overtimes', function (Blueprint $table) {
                $table->increments('id');
                $table->string('pen');
                $table->string('name');
                $table->string('designation');
                $table->string('from');
                $table->string('to');
                $table->integer('count')->nullable();
                $table->integer('rate')->nullable();
                $table->string('worknature')->nullable();
                $table->timestamps();
                
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('overtimes');
    }
}
