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
        Schema::table('overtimes', function (Blueprint $table) {
        
          //if(!Schema::hasColumn( 'overtimes','punching'))
          {
            // $table->unique('category'); 
            //default 0 to make sure old forms does not have punching
            $table->boolean('punching')->default(0)->nullable();
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
        Schema::table('overtimes', function (Blueprint $table) {
          if(Schema::hasColumn( 'overtimes','punching'))
          {
            $table->dropColumn('punching');
          }
        });
    }
};
