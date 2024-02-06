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
                $table->string('punch_in');
                $table->string('punch_out');
                $table->string('pen'); //duplication, just for query
                $table->unique(['date', 'pen']);
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
        Schema::dropIfExists('punchings');
    }
};
