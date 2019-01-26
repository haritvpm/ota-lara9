<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormOthersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_others', function (Blueprint $table) {
            $table->increments('id');
                $table->string('session');
                $table->string('creator');
                $table->string('owner')->nullable();
                $table->string('submitted_by')->nullable();
                $table->date('submitted_on')->nullable();
                $table->enum('overtime_slot', array('First', 'Second', 'Third', 'Sittings'));
                $table->integer('form_no')->nullable();
                
                $table->date('duty_date')->nullable();
                $table->date('date_from')->nullable();
                $table->date('date_to')->nullable();
                $table->string('remarks')->nullable();
               
                
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
        Schema::dropIfExists('form_others');
    }
}
