<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Create1517456188ExemptionformsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(! Schema::hasTable('exemptionforms')) {
            Schema::create('exemptionforms', function (Blueprint $table) {
                $table->increments('id');
                $table->string('session');
                $table->string('creator');
                $table->string('owner')->nullable();
                $table->integer('form_no')->nullable();
                $table->string('submitted_names')->nullable();
                $table->string('submitted_by')->nullable();
                $table->date('submitted_on')->nullable();
                $table->string('remarks')->nullable();
                
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
        Schema::dropIfExists('exemptionforms');
    }
}
