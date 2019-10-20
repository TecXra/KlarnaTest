<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_data', function (Blueprint $table) {
            // $table->engine = 'InnoDB';
            $table->engine = 'MyISAM';
            $table->increments('id');
            $table->integer('order_id')->unsigned();
            $table->string('reg_number')->nullable();
            $table->string('car_model')->nullable();;
            $table->string('front_tire')->nullable();;
            $table->string('pcd')->nullable();;
            $table->string('offset')->nullable();;
            $table->string('nav')->nullable();;
            $table->string('oe_type')->nullable();;
            $table->timestamps();
        });

        Schema::table('car_data', function($table) {
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
        Schema::drop('car_data');
    }
}
