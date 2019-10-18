<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->engine = 'MyISAM';
            $table->increments('id');
            $table->integer('user_type_id')->unsigned();
            $table->integer('payment_type_id')->unsigned();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('company_name')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('date_of_birth', 20)->nullable();
            $table->string('org_number', 30)->nullable();
            $table->string('billing_full_name');
            $table->string('billing_phone', 30);
            $table->string('billing_street_address');
            $table->integer('billing_postal_code')->length(5);
            $table->string('billing_city', 20);
            $table->string('billing_country', 30);
            $table->string('shipping_full_name');
            $table->string('shipping_phone', 30);
            $table->string('shipping_street_address');
            $table->integer('shipping_postal_code')->length(5);
            $table->string('shipping_city', 20);
            $table->string('shipping_country', 30);
            $table->integer('credit_card');
            $table->string('credit_card_type', 20);
            $table->date('credit_exp_date');
            $table->boolean('is_company');
            $table->boolean('is_active');
            $table->rememberToken();
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
        Schema::drop('users');
    }
}
