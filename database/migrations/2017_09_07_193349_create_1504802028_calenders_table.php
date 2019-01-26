<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Create1504802028CalendersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(! Schema::hasTable('calenders')) {
            Schema::create('calenders', function (Blueprint $table) {
                $table->increments('id');
                $table->date('date')->unique();
                $table->enum('day_type', array('Sitting day',  'Prior holiday', 'Prior Working day', 'Holiday', 'Intervening saturday', 'Intervening Working day'));

                  $table->string('description')->nullable();
                
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
        Schema::dropIfExists('calenders');
    }
}
