<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToDesignationsTable extends Migration
{
    public function up()
    {
        Schema::table('designations', function (Blueprint $table) {
          
            $table->integer('office_time_id')->unsigned()->nullable();
            $table->foreign('office_time_id', 'office_time_fk_9884847')->references('id')->on('office_times');

            $table->string('type')->nullable();
            $table->boolean('has_additional_ot')->default(0)->nullable();

        });
    }
}
