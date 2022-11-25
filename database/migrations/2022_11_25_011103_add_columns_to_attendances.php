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
        Schema::table('attendances', function (Blueprint $table) {
            if (!Schema::hasColumn('attendances', 'pen')) {
                $table->string('pen');
                $table->integer('total');
                $table->string('dates_present')->nullable();
               
                
            }

            if (Schema::hasColumn('attendances', 'date_absent')) {
                $table->dropColumn('date_absent');
                
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
        Schema::table('attendances', function (Blueprint $table) {
           /*  $table->dropColumn('pen');
            $table->dropColumn('total');
            $table->dropColumn('dates_present'); */

        });
    }
};
