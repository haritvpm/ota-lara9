<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('devices');
        Schema::dropIfExists('govt_calendars');
        Schema::dropIfExists('section_employees');
        Schema::dropIfExists('user_employees');
        Schema::dropIfExists('officer_mappings');
        Schema::dropIfExists('officer_employees');
        Schema::dropIfExists('sections');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
