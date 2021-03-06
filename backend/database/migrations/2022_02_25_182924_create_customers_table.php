<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->bigIncrements('ID');
            $table->string('f_name', 20);
            $table->string('s_name', 20);
            $table->string('f_lastname', 20);
            $table->string('s_lastname', 20);
            $table->string('email', 50);
            $table->string('home', 50);
            $table->bigInteger('id_number');
            $table->bigInteger('cellphone');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
