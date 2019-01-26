<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Create1507685434EmployeesOthersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(! Schema::hasTable('employees_others')) {
            Schema::create('employees_others', function (Blueprint $table) {
                $table->increments('id');
                $table->enum('srismt', array('Sri', 'Smt', 'Kum'));
                $table->string('name');
                $table->string('pen');
                $table->string('department_idno')->nullable();
                $table->enum('account_type', array('Bank Account', 'TSB'));
                $table->string('ifsc')->nullable();
                $table->string('account_no');
                $table->string('mobile');
                $table->string('added_by');
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
        Schema::dropIfExists('employees_others');
    }
}
