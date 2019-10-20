<?php

use Illuminate\Database\Seeder;

class OrderDetailTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('order_details')->delete();
  
        DB::table('order_details')->insert([
            'product_id' 	=>	123,
            'order_id' =>	1,
            'quantity' =>	4,
            'discount' =>	0,
            'unit_price' =>	543,
            'total_price_excluding_tax' =>	2200,
            'total_price_including_tax' =>	2500,
            'total_tax_amount' =>	300,
            
        ]);

        DB::table('order_details')->insert([
            'product_id' 	=>	42123,
            'order_id' =>	1,
            'quantity' =>	4,
            'discount' =>	0,
            'unit_price' =>	843,
            'total_price_excluding_tax' =>	3435,
            'total_price_including_tax' =>	3670,
            'total_tax_amount' =>	735,
            
        ]);

        DB::table('order_details')->insert([
            'product_id' 	=>	12312,
            'order_id' =>	2,
            'quantity' =>	2,
            'discount' =>	0,
            'unit_price' =>	988,
            'total_price_excluding_tax' =>	1935,
            'total_price_including_tax' =>	2670,
            'total_tax_amount' =>	795,
            
        ]);

        DB::table('order_details')->insert([
            'product_id' 	=>	23412,
            'order_id' =>	3,
            'quantity' =>	4,
            'discount' =>	0,
            'unit_price' =>	388,
            'total_price_excluding_tax' =>	1435,
            'total_price_including_tax' =>	1770,
            'total_tax_amount' =>	335,
            
        ]);

        DB::table('order_details')->insert([
            'product_id' 	=>	29412,
            'order_id' =>	3,
            'quantity' =>	4,
            'discount' =>	0,
            'unit_price' =>	418,
            'total_price_excluding_tax' =>	1655,
            'total_price_including_tax' =>	2070,
            'total_tax_amount' =>	435,
            
        ]);

        DB::table('order_details')->insert([
            'product_id' 	=>	41412,
            'order_id' =>	3,
            'quantity' =>	4,
            'discount' =>	0,
            'unit_price' =>	718,
            'total_price_excluding_tax' =>	2835,
            'total_price_including_tax' =>	3370,
            'total_tax_amount' =>	535,
        ]);
    }
}
