<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->bigIncrements('ID');
            $table->string('name', 50);
            $table->string('location', 100);
            $table->string('mission', 500);
            $table->string('vision', 500);
            $table->string('about_us', 500);
            $table->string('email', 75);
            $table->string('twitter', 50);
            $table->string('instagram', 50);
            $table->integer('f_phone');
            $table->integer('s_phone');
            $table->integer('cellphone');
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
        Schema::dropIfExists('businesses');
    }
}
