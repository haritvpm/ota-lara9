<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToOfficerMappingsTable extends Migration
{
    public function up()
    {
        Schema::table('officer_mappings', function (Blueprint $table) {
            $table->unsignedInteger('section_or_officer_user_id')->nullable();
            $table->foreign('section_or_officer_user_id', 'section_or_officer_user_fk_9584016')->references('id')->on('users');
            $table->unsignedInteger('controlling_officer_user_id')->nullable();
            $table->foreign('controlling_officer_user_id', 'controlling_officer_user_fk_9584018')->references('id')->on('users');
        });
    }
}
