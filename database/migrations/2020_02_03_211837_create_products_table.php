<?php

use App\Product;
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
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('description',1000);
            $table->integer('quantity')->unsigned(); //permite que no tenga signos osea solo numeros positivos;
            $table->string('status')->default(Product::PRODUCTO_NO_DISPONIBLE);
            $table->string('img');
            $table->unsignedBigInteger('seller_id')->unsigned();

            $table->foreign('seller_id')->references('id')->on('users');
            $table->timestamps();
            
            $table->softDeletes();
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
