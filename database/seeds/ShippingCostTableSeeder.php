<?php

use Illuminate\Database\Seeder;

class ShippingCostTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('shipping_cost')->delete();

        DB::table('shipping_cost')->insert([
            'product_category_id'  =>  1,
            'product_type_id'  =>  1,
            // 'cost' =>   100,
            'cost' =>	0,
        ]);

        DB::table('shipping_cost')->insert([
            'product_category_id'  =>  1,
            'product_type_id'  =>  2,
            // 'cost' =>   100,
            'cost' =>   0,
        ]);

        DB::table('shipping_cost')->insert([
            'product_category_id'  =>  1,
            'product_type_id'  =>  3,
            // 'cost' =>   100,
            'cost' =>   0,
        ]);

        DB::table('shipping_cost')->insert([
            'product_category_id'  =>  2,
            'product_type_id'  =>  4,
            // 'cost' =>   100,
            'cost' =>	0,
        ]);

        DB::table('shipping_cost')->insert([
            'product_category_id'  =>  2,
            'product_type_id'  =>  5,
            // 'cost' =>   100,
            'cost' =>   0,
        ]);

        DB::table('shipping_cost')->insert([
            'product_category_id'  =>  2,
            'product_type_id'  =>  6,
            // 'cost' =>   100,
            'cost' =>   0,
        ]);

        DB::table('shipping_cost')->insert([
            'product_category_id'  =>  3,
            'product_type_id'  =>  7,
            // 'cost' =>   100,
            'cost' =>	0,
        ]);

        DB::table('shipping_cost')->insert([
            'product_category_id'  =>  3,
            'product_type_id'  =>  8,
            // 'cost' =>   100,
            'cost' =>	0,
        ]);

        DB::table('shipping_cost')->insert([
            'product_category_id'  =>  3,
            'product_type_id'  =>  9,
            // 'cost' =>   100,
            'cost' =>	0,
        ]);

        DB::table('shipping_cost')->insert([
            'product_category_id'  =>  3,
            'product_type_id'  =>  10,
            // 'cost' =>   100,
            'cost' =>	0,
        ]);

        DB::table('shipping_cost')->insert([
            'product_category_id'  =>  4,
            'product_type_id'  =>  11,
            'cost' =>   0,
        ]);

        DB::table('shipping_cost')->insert([
            'product_category_id'  =>  3,
            'product_type_id'  =>  12,
            // 'cost' =>   100,
            'cost' =>   0,
        ]);

        DB::table('shipping_cost')->insert([
            'product_category_id'  =>  3,
            'product_type_id'  =>  13,
            // 'cost' =>   100,
            'cost' =>   0,
        ]);

        DB::table('shipping_cost')->insert([
            'product_category_id'  =>  4,
            'product_type_id'  =>  14,
            'cost' =>   0,
        ]);

        DB::table('shipping_cost')->insert([
            'product_category_id'  =>  4,
            'product_type_id'  =>  15,
            'cost' =>   0,
        ]);

        DB::table('shipping_cost')->insert([
            'product_category_id'  =>  3,
            'product_type_id'  =>  16,
            // 'cost' =>   100,
            'cost' =>   0,
        ]);

        DB::table('shipping_cost')->insert([
            'product_category_id'  =>  3,
            'product_type_id'  =>  17,
            // 'cost' =>   100,
            'cost' =>   0,
        ]);

    }
}
