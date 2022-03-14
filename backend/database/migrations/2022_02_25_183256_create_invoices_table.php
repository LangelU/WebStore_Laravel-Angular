<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->bigIncrements('ID');
            $table->integer('invoice_number');
            $table->unsignedBigInteger('business_name');
            $table->unsignedBigInteger('business_phone');
            $table->unsignedBigInteger('business_phone2');
            $table->unsignedBigInteger('user_name');
            $table->unsignedBigInteger('user_idnumber');
            $table->unsignedBigInteger('user_cellphone');
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
        Schema::dropIfExists('invoices');
    }
}
