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
        if(!Schema::hasTable('punchings')) {
            Schema::create('punchings', function (Blueprint $table) {
                $table->increments('id');
                $table->date('date');
                $table->string('punch_in')->nullable();
                $table->string('punch_out')->nullable();
                $table->string('pen')->nullable();; //duplication, just for query
                $table->string('creator')->nullable();
            //    $table->string('session')->nullable();
              //  $table->unique(['date', 'pen']); //pen can be null in aebas
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                
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
        Schema::dropIfExists('punchings');
    }
};
