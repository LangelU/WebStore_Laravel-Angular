<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('ID');
            $table->string('reference', 50);
            $table->string('name', 50);
            $table->string('type', 100);
            $table->string('description', 1000);
            $table->string('details', 1000);
            $table->bigInteger('price');
            $table->unsignedBigInteger('ID_category');
            $table->bigInteger('stock');
            $table->string('brand', 50);
            $table->string('model', 50);
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
        Schema::dropIfExists('products');
    }
}
