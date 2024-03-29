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
        Schema::table('forms', function (Blueprint $table) {
         //   $table->enum('overtime_slot', ['Multi','First', 'Second', 'Third', 'Sittings', 'Additional'])->change();
         DB::statement("ALTER TABLE forms MODIFY COLUMN overtime_slot enum('Multi','First', 'Second', 'Third', 'Sittings', 'Additional') ");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('forms', function (Blueprint $table) {
            //
        });
    }
};
