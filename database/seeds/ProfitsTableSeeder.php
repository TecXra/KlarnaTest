<?php

use Illuminate\Database\Seeder;

class ProfitsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('profits')->delete();

        // For accessories products
        DB::table('profits')->insert([
          'product_type' => 4,
        ]);

        DB::table('profits')->insert([
        	'product_type' => 1,
        	'size' => -1,
        	'in_procent' => 0,
        	'in_cash' => 0
       	]);

        DB::table('profits')->insert([
        	'product_type' => 1,
        	'size' => 12,
        	'in_procent' => 0,
        	'in_cash' => 0
       	]);

       	DB::table('profits')->insert([
        	'product_type' => 1,
        	'size' => 13,
        	'in_procent' => 0,
        	'in_cash' => 0
       	]);

       	DB::table('profits')->insert([
        	'product_type' => 1,
        	'size' => 14,
        	'in_procent' => 0,
        	'in_cash' => 0
       	]);

       	DB::table('profits')->insert([
        	'product_type' => 1,
        	'size' => 15,
        	'in_procent' => 0,
        	'in_cash' => 0
       	]);

       	DB::table('profits')->insert([
        	'product_type' => 1,
        	'size' => 16,
        	'in_procent' => 0,
        	'in_cash' => 0
       	]);

       	DB::table('profits')->insert([
        	'product_type' => 1,
        	'size' => 17,
        	'in_procent' => 0,
        	'in_cash' => 0
       	]);

       	DB::table('profits')->insert([
        	'product_type' => 1,
        	'size' => 18,
        	'in_procent' => 0,
        	'in_cash' => 0
       	]);

       	DB::table('profits')->insert([
        	'product_type' => 1,
        	'size' => 19,
        	'in_procent' => 0,
        	'in_cash' => 0
       	]);

       	DB::table('profits')->insert([
        	'product_type' => 1,
        	'size' => 20,
        	'in_procent' => 0,
        	'in_cash' => 0
       	]);

       	DB::table('profits')->insert([
        	'product_type' => 1,
        	'size' => 21,
        	'in_procent' => 0,
        	'in_cash' => 0
       	]);

       	DB::table('profits')->insert([
        	'product_type' => 1,
        	'size' => 22,
        	'in_procent' => 0,
        	'in_cash' => 0
       	]);

       	DB::table('profits')->insert([
        	'product_type' => 1,
        	'size' => 1,
        	'in_procent' => 0,
        	'in_cash' => 0
       	]);

       	DB::table('profits')->insert([
        	'product_type' => 2,
        	'size' => -1,
        	'in_procent' => 0,
        	'in_cash' => 0
       	]);

       	DB::table('profits')->insert([
        	'product_type' => 2,
        	'size' => 12,
        	'in_procent' => 0,
        	'in_cash' => 0
       	]);

       	DB::table('profits')->insert([
        	'product_type' => 2,
        	'size' => 13,
        	'in_procent' => 0,
        	'in_cash' => 0
       	]);

       	DB::table('profits')->insert([
        	'product_type' => 2,
        	'size' => 14,
        	'in_procent' => 0,
        	'in_cash' => 0
       	]);

       	DB::table('profits')->insert([
        	'product_type' => 2,
        	'size' => 15,
        	'in_procent' => 0,
        	'in_cash' => 0
       	]);

       	DB::table('profits')->insert([
        	'product_type' => 2,
        	'size' => 16,
        	'in_procent' => 0,
        	'in_cash' => 0
       	]);

       	DB::table('profits')->insert([
        	'product_type' => 2,
        	'size' => 17,
        	'in_procent' => 0,
        	'in_cash' => 0
       	]);

       	DB::table('profits')->insert([
        	'product_type' => 2,
        	'size' => 18,
        	'in_procent' => 0,
        	'in_cash' => 0
       	]);

       	DB::table('profits')->insert([
        	'product_type' => 2,
        	'size' => 19,
        	'in_procent' => 0,
        	'in_cash' => 0
       	]);

       	DB::table('profits')->insert([
        	'product_type' => 2,
        	'size' => 20,
        	'in_procent' => 0,
        	'in_cash' => 0
       	]);

       	DB::table('profits')->insert([
        	'product_type' => 2,
        	'size' => 21,
        	'in_procent' => 0,
        	'in_cash' => 0
       	]);

       	DB::table('profits')->insert([
        	'product_type' => 2,
        	'size' => 22,
        	'in_procent' => 0,
        	'in_cash' => 0
       	]);

       	DB::table('profits')->insert([
        	'product_type' => 2,
        	'size' => 1,
        	'in_procent' => 0,
        	'in_cash' => 0
       	]);

       	DB::table('profits')->insert([
        	'product_type' => 3,
        	'size' => -1,
        	'in_procent' => 0,
        	'in_cash' => 0
       	]);

       	DB::table('profits')->insert([
        	'product_type' => 3,
        	'size' => 12,
        	'in_procent' => 0,
        	'in_cash' => 0,
        	'mount' => 180
       	]);

       	DB::table('profits')->insert([
        	'product_type' => 3,
        	'size' => 13,
        	'in_procent' => 0,
        	'in_cash' => 0,
        	'mount' => 180
       	]);

       	DB::table('profits')->insert([
        	'product_type' => 3,
        	'size' => 14,
        	'in_procent' => 0,
        	'in_cash' => 0,
        	'mount' => 180
       	]);

       	DB::table('profits')->insert([
        	'product_type' => 3,
        	'size' => 15,
        	'in_procent' => 0,
        	'in_cash' => 0,
        	'mount' => 180
       	]);

       	DB::table('profits')->insert([
        	'product_type' => 3,
        	'size' => 16,
        	'in_procent' => 0,
        	'in_cash' => 0,
        	'mount' => 180
       	]);

       	DB::table('profits')->insert([
        	'product_type' => 3,
        	'size' => 17,
        	'in_procent' => 0,
        	'in_cash' => 0,
        	'mount' => 180
       	]);

       	DB::table('profits')->insert([
        	'product_type' => 3,
        	'size' => 18,
        	'in_procent' => 0,
        	'in_cash' => 0,
        	'mount' => 180
       	]);

       	DB::table('profits')->insert([
        	'product_type' => 3,
        	'size' => 19,
        	'in_procent' => 0,
        	'in_cash' => 0,
        	'mount' => 180
       	]);

       	DB::table('profits')->insert([
        	'product_type' => 3,
        	'size' => 20,
        	'in_procent' => 0,
        	'in_cash' => 0,
        	'mount' => 180
       	]);

       	DB::table('profits')->insert([
        	'product_type' => 3,
        	'size' => 21,
        	'in_procent' => 0,
        	'in_cash' => 0,
        	'mount' => 180
       	]);

       	DB::table('profits')->insert([
        	'product_type' => 3,
        	'size' => 22,
        	'in_procent' => 0,
        	'in_cash' => 0,
        	'mount' => 180
       	]);

       	DB::table('profits')->insert([
        	'product_type' => 3,
        	'size' => 1,
        	'in_procent' => 0,
        	'in_cash' => 0
       	]); 

    }
}
