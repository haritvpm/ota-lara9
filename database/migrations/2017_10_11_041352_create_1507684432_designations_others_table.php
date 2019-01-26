<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Create1507684432DesignationsOthersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(! Schema::hasTable('designations_others')) {
            Schema::create('designations_others', function (Blueprint $table) {
                $table->increments('id');
                $table->string('designation');
                $table->integer('rate')->nullable()->unsigned();
                $table->integer('max_persons')->nullable()->default(-1) ;        
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
        Schema::dropIfExists('designations_others');
    }
}
