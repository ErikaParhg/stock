<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->integer('supplier_id')->unsigned()->index(); 
            $table->string('name');
            $table->string('description');
            $table->float('cost');
            $table->integer('quantity');          
            $table->timestamps();
        });


        Schema::table('products', function (Blueprint $table) {
           $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');;
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
