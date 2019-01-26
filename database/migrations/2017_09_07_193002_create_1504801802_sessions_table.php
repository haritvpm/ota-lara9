<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Create1504801802SessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(! Schema::hasTable('sessions')) {
            Schema::create('sessions', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name')->unique();
                $table->integer('kla')->nullable()->unsigned();
                $table->integer('session')->nullable()->unsigned();
                $table->enum('dataentry_allowed', array('Yes', 'No'));
                $table->enum('show_in_datatable', array('Yes', 'No'))->nullable();
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
        Schema::dropIfExists('sessions');
    }
}
