<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Create1504801473EmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(! Schema::hasTable('employees')) {
            Schema::create('employees', function (Blueprint $table) {
                $table->increments('id');
                $table->enum('srismt', array('Sri', 'Smt', 'Kum'));
                $table->string('name');
               
                $table->string('pen')->unique();
                $table->string('added_by');
                $table->enum('category', array('Staff', 'Provisional','Staff - Admin Data Entry'))->default('Staff');
                $table->string('excel_category')->nullable();
                $table->string('desig_display')->nullable();;

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
        Schema::dropIfExists('employees');
    }
}
