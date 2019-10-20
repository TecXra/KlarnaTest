<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            // $table->engine = 'InnoDB';
            $table->engine = 'MyISAM';
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('shipper_id')->unsigned();
            $table->integer('campaign_code')->unsigned();
            $table->string('klarna_reference')->nullable();
            $table->string('klarna_reservation')->nullable();
            $table->string('klarna_invoice_no', 30);
            $table->string('klarna_risk', 20);
            $table->string('klarna_status', 20);
            $table->timestamp('klarna_start_date')->nullable();
            $table->integer('svea_order_id')->nullable();
            $table->string('dibs_transact')->nullable();
            $table->string('authorization_id')->nullable();
            $table->string('batch_number')->nullable();
            $table->string('transaction_id')->nullable();
            $table->tinyInteger('is_payment_rejected')->default(0);
            $table->integer('order_status_id')->length(3);
            $table->string('payment_type');
            $table->string('card_type')->nullable();
            $table->string('masked_card_number')->nullable();
            $table->boolean('is_company');
            $table->string('org_number', 30)->nullable();
            $table->string('ip_number')->nullable();
            $table->float('payment_fee');
            $table->integer('payment_method_id')->length(2);
            $table->integer('delivery_method_id')->length(2);
            $table->float('shipping_fee');
            $table->datetime('transaction_date');
            $table->datetime('shipping_date');
            $table->datetime('payment_date');
            $table->float('total_price_excluding_tax');
            $table->float('total_price_including_tax');
            $table->float('total_tax_amount');
            $table->float('discount');
            $table->string('currency', 10);
            $table->string('currency_notation', 5);
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
            $table->boolean('is_ordered')->nullable();
            $table->boolean('is_pushed')->default(0);
            $table->boolean('is_affiliate_customer')->default(0);
            $table->string('delivery_time', 100);
            $table->string('reference')->nullable();
            $table->text('message')->nullable();
            $table->text('comment')->nullable();
            $table->string('token', 100);
            $table->timestamps();
        });

        Schema::table('Orders', function($table) {
            // $table->foreign('user_id')->references('id')->on('users');
            // $table->foreign('shipper_id')->references('id')->on('shippers');
            // $table->foreign('payment_id')->references('id')->on('payments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('orders');
    }
}
