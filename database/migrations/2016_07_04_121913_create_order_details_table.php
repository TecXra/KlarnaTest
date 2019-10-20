<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            // $table->engine = 'InnoDB';
            $table->engine = 'MyISAM';
            $table->increments('id');
            $table->integer('order_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->string('main_supplier_product_id')->nullable();
            $table->string('product_name');
            $table->integer('quantity')->length(5);
            $table->float('discount')->nullable();
            $table->float('net_price');
            $table->float('unit_price');
            $table->float('total_price_excluding_tax');
            $table->float('total_price_including_tax');
            $table->float('total_tax_amount');
            $table->float('tax');
            $table->string('currency', 10);
            $table->string('ajust')->nullable();
            $table->boolean('is_ordered')->nullable();
            $table->timestamps();
        });

        Schema::table('OrderDetails', function($table) {
            // $table->foreign('product_id')->references('id')->on('products');
            // $table->foreign('order_id')->references('id')->on('orders');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('order_details');
    }
}
