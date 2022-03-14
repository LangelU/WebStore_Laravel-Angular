<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClaimsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('claims', function (Blueprint $table) {
            $table->bigIncrements('ID');
            $table->bigInteger('requestNumber');
            $table->bigInteger('invoice_number');
            $table->bigInteger('idnumber');
            $table->string('f_name', 25);
            $table->string('f_lastname', 25);
            $table->string('request_type', 50);
            $table->string('details', 1000);
            $table->integer('state');
            $table->string('answer', 1000)->nullable();
            $table->string('attended_by', 100)->nullable();
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
        Schema::dropIfExists('claims');
    }
}
