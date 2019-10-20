<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            // $table->engine = 'InnoDB';
            $table->engine = 'MyISAM';
            $table->increments('id');
            // $table->integer('user_id')->unsigned()->nullable();
            $table->string('company_name', 50);
            // $table->string('email', 50);
            // $table->string('street_address1');
            // $table->integer('postal_code1')->length(5);
            // $table->string('city1', 20);
            // $table->string('country1', 30);
            // $table->string('street_address2');
            // $table->integer('postal_code2')->length(5);
            // $table->string('city2', 20);
            // $table->string('country2', 30);
            // $table->string('phone', 20);
            // $table->integer('ssn')->length(10);
            // $table->string('site_url');
            $table->timestamps();
        });

        Schema::table('Suppliers', function($table) {
            // $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('suppliers');
    }
}
