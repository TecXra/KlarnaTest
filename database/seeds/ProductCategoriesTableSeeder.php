<?php

use Illuminate\Database\Seeder;

class ProductCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('product_categories')->delete();
  
        DB::table('product_categories')->insert([
            'name' 	=>	'Tires',
        ]);

        DB::table('product_categories')->insert([
            'name' 	=>	'Rims',
        ]);

        DB::table('product_categories')->insert([
            'name' 	=>	'Accessories',
        ]);

        DB::table('product_categories')->insert([
            'name'  =>  'Other',
        ]);
    }
}

