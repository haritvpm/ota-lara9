<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToSectionsTable extends Migration
{
    public function up()
    {
        Schema::table('sections', function (Blueprint $table) {
            $table->unsignedInteger('officer_id')->nullable();
            $table->foreign('officer_id', 'officer_fk_9584060')->references('id')->on('users');
        });
    }
}
