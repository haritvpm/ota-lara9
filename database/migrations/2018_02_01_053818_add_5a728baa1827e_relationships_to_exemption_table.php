<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Add5a728baa1827eRelationshipsToExemptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exemptions', function(Blueprint $table) {
            if (!Schema::hasColumn('exemptions', 'exemptionform_id')) {
                $table->integer('exemptionform_id')->unsigned()->nullable();
                $table->foreign('exemptionform_id', '113538_5a728ba82bff4')->references('id')->on('exemptionforms')->onDelete('cascade');
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
        Schema::table('exemptions', function(Blueprint $table) {
            
        });
    }
}
