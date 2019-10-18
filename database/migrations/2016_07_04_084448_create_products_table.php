<?php

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
            // $table->engine = 'InnoDB';
            $table->engine = 'MyISAM';
            $table->increments('id');
            $table->integer('product_category_id')->unsigned();
            $table->integer('product_type_id')->unsigned();
            $table->integer('gg_group_id')->unsigned();
            $table->integer('profit_id')->unsigned();
            $table->integer('main_supplier_id')->unsigned();
            $table->string('main_supplier_product_id')->nullable();
            $table->integer('sub_supplier_id')->nullable();
            $table->string('product_brand', 50)->nullable();
            $table->integer('gg_brand_id')->unsigned();
            $table->string('product_code', 100)->nullable();;
            $table->text('product_description')->nullable();
            $table->string('product_color', 50)->nullable();
            $table->string('product_model', 50)->nullable();
            $table->string('product_name', 100)->nullable();
            $table->string('product_dimension', 255)->nullable();
            $table->string('product_inner_dimension', 15)->nullable(); //Endast för ringar
            $table->float('product_width')->length(5)->nullable();
            $table->integer('product_profile')->length(5)->nullable();;
            $table->integer('product_inch')->length(5)->nullable();
            $table->string('product_e_code', 50)->nullable();
            $table->string('ean', 25)->nullable();
            $table->integer('et')->length(3)->nullable(); // bör ändras till et_max senare
            $table->integer('et_min')->length(3)->nullable();
            $table->integer('storage_et')->length(3)->nullable();
            $table->boolean('is_ctyre')->default(0)->nullable();
            $table->boolean('is_runflat')->default(0)->nullable();
            $table->string('tire_manufactor_date', 10)->nullable();
            $table->string('load_index', 8)->nullable();
            $table->string('speed_index', 3)->nullable();
            $table->string('product_label', 15)->nullable();
            $table->string('rolling_resistance', 3)->nullable();
            $table->string('wet_grip', 3)->nullable();
            $table->integer('noise_emission_rating')->length(3)->nullable();
            $table->integer('noise_emission_decibel')->length(3)->nullable();
            $table->string('pcd1', 10)->nullable();
            $table->string('pcd2', 10)->nullable();
            $table->string('pcd3', 10)->nullable();
            $table->string('pcd4', 10)->nullable();
            $table->string('pcd5', 10)->nullable();
            $table->string('pcd6', 10)->nullable();
            $table->string('pcd7', 10)->nullable();
            $table->string('pcd8', 10)->nullable();
            $table->string('pcd9', 10)->nullable();
            $table->string('pcd10', 10)->nullable();
            $table->string('pcd11', 10)->nullable();
            $table->string('pcd12', 10)->nullable();
            $table->string('pcd13', 10)->nullable();
            $table->string('pcd14', 10)->nullable();
            $table->string('pcd15', 10)->nullable();
            $table->string('storage_pcd', 10)->nullable();;
            $table->string('storage_location', 30)->nullable();
            $table->float('bore_min')->nullable();
            $table->float('bore_max')->nullable();
            $table->float('calculated_price')->nullable();
            $table->float('price');
            $table->float('original_price')->nullable();
            $table->float('storage_price')->nullable();
            $table->integer('quantity')->length(5);
            $table->integer('min_orderble_quantity')->length(5)->default(1);
            $table->float('discount1')->default(1);
            $table->float('discount2')->default(1);
            $table->float('discount3')->default(1);
            $table->float('discount4')->default(1);
            $table->string('video_link')->nullable();;
            $table->string('three_d_link')->nullable();
            $table->float('environmental_fee')->nullable();;
            $table->integer('priority')->length(2)->default(0);
            $table->integer('priority_supplier')->length(2)->default(0);
            $table->boolean('is_winter_rim')->nullable();
            $table->boolean('is_euro_storage')->nullable();
            $table->boolean('is_in_storage')->nullable();
            $table->boolean('is_order_item')->nullable();
            $table->boolean('is_featured')->nullable();
            $table->boolean('is_shown');
            $table->boolean('is_deleted')->default(0);
            $table->string('delivery_time')->nullable();
            $table->date('available_at')->nullable();
            $table->date('supplier_posted_at')->nullable();
            $table->timestamps();
        });

        Schema::table('products', function($table) {
            $table->index(['main_supplier_id', 'main_supplier_product_id'], 'supplier_articleId_index');
            $table->index('product_category_id');
            $table->index('product_type_id');
            $table->index(['main_supplier_id', 'product_type_id', 'product_inch', 'product_width', 'product_brand', 'main_supplier_product_id'], 'main_index');

            // $table->foreign('product_category_id')->references('id')->on('product_categories');
            // $table->foreign('main_supplier_id')->references('id')->on('suppliers');
            // $table->foreign('product_type_id')->references('id')->on('product_type');
            // $table->foreign('profit_id')->references('id')->on('profits');
            // $table->foreign('SubSupplierID')->references('SupplierID')->on('Suppliers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('products');
    }
}
