<?php

use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->delete();

        DB::table('products')->insert([
            'product_category_id'  =>  '3',
            'product_type_id'  =>  '7',
            'profit_id'  =>  '1',
            'main_supplier_id'  =>  '1',
            'main_supplier_product_id'  =>  '124',
            // 'main_supplier_product_id'  =>  'TPMS 433Mhz',
            'product_code' =>   'TPMS Däcktryckssensor bilanpassad efter reg.nummer',
            'product_name' =>   'TPMS Däcktryckssensor bilanpassad efter reg.nummer',
            'product_description' => 'TPMS SENSOR 433Mhz',
            'price' => 476,
            'quantity' => '100',
            'discount1' => 1,
            'discount2' => 1,
            'discount3' => 1,
            'discount4' => 1,
            'is_shown' => 1
        ]);

        DB::table('products')->insert([
            'product_category_id'  =>  '3',
            'product_type_id'  =>  '11',
            'profit_id'  =>  '1',
            'main_supplier_id'  =>  '1',
            'main_supplier_product_id'  =>  '122',
            'product_name' =>   'Monteringskit: Bult/Mutter/Navring',
            'product_code' =>   'Monteringskit: Bult/Mutter/Navring',
            'price' => 316,
            'quantity' => '100',
            'discount1' => 1,
            'discount2' => 1,
            'discount3' => 1,
            'discount4' => 1,
            'is_shown' => 1
        ]);
  
        // DB::table('products')->insert([
        //     'product_category_id'  =>  '4',
        //     'product_type_id'  =>  '14',
        //     'profit_id'  =>  '1',
        //     'main_supplier_id'  =>  '1',
        //     'product_code' =>   'Tjänster',
        //     'product_name' =>   'Montering + Balansering (Däck på fälg)',
        //     'product_dimension' => 'Montering + Balansering (Däck på fälg)',
        //     'price' => 144,
        //     'quantity' => '100',
        //     'discount1' => 1,
        //     'discount2' => 1,
        //     'discount3' => 1,
        //     'discount4' => 1,
        //     'is_shown' => 1
        // ]);

        // DB::table('products')->insert([
        //     'product_category_id'  =>  '4',
        //     'product_type_id'  =>  '14',
        //     'profit_id'  =>  '1',
        //     'main_supplier_id'  =>  '1',
        //     'main_supplier_product_id'  =>  'Skifte på bil',
        //     'product_code' =>   'Tjänster',
        //     'product_name' =>   'Skifte på bil',
        //     'product_description' => '',
        //     'product_dimension' => 'Skifte på bil',
        //     'price' => 200,
        //     'quantity' => '100',
        //     'discount1' => 1,
        //     'discount2' => 0.38,
        //     'discount3' => 0.38,
        //     'discount4' => 0.38,
        //     'is_shown' => 1
        // ]);

         DB::table('products')->insert([
            'product_category_id'  =>  '3',
            'product_type_id'  =>  '12',
            'profit_id'  =>  '1',
            'main_supplier_id'  =>  '1',
            'main_supplier_product_id'  =>  '132',
            // 'main_supplier_product_id'  =>  'Låsbult/ Mutter kit',
            'product_code' =>   'Låsbult/ Mutter kit',
            'product_name' =>   'Låsbult/ Mutter kit',
            'product_description' => '',
            'price' => 200,
            'quantity' => '100',
            'discount1' => 1,
            'discount2' => 1,
            'discount3' => 1,
            'discount4' => 1,
            'is_shown' => 1
        ]);

        // DB::table('products')->insert([
        //     'product_category_id'  =>  '3',
        //     'product_type_id'  =>  '9',
        //     'profit_id'  =>  '1',
        //     'main_supplier_id'  =>  '1',
        //     'main_supplier_product_id'  =>  'Bult 12x1.25 Hex 17',
        //     'product_code' =>   'Bultar',
        //     'product_name' =>   'Bult 12x1.25 Hex 17',
        //     'product_description' => '',
        //     'product_dimension' => '12x1.25 Hex 17',
        //     'price' => 10,
        //     'quantity' => '100',
        //     'discount1' => 1,
        //     'discount2' => 1,
        //     'discount3' => 1,
        //     'discount4' => 1,
        //     'is_shown' => 1
        // ]);

        // DB::table('products')->insert([
        //     'product_category_id'  =>  '3',
        //     'product_type_id'  =>  '9',
        //     'profit_id'  =>  '1',
        //     'main_supplier_id'  =>  '1',
        //     'main_supplier_product_id'  =>  'Bult 12x1.5 Hex 17',
        //     'product_code' =>   'Bultar',
        //     'product_name' =>   'Bult 12x1.5 Hex 17',
        //     'product_description' => '',
        //     'product_dimension' => '12x1.5 Hex 17',
        //     'price' => 10,
        //     'quantity' => '100',
        //     'discount1' => 1,
        //     'discount2' => 1,
        //     'discount3' => 1,
        //     'discount4' => 1,
        //     'is_shown' => 1
        // ]);

        // DB::table('products')->insert([
        //     'product_category_id'  =>  '3',
        //     'product_type_id'  =>  '9',
        //     'profit_id'  =>  '1',
        //     'main_supplier_id'  =>  '1',
        //     'main_supplier_product_id'  =>  'Bult 12x1.75 Hex 17',
        //     'product_code' =>   'Bultar',
        //     'product_name' =>   'Bult 12x1.75 Hex 17',
        //     'product_description' => '',
        //     'product_dimension' => '12x1.75 Hex 17',
        //     'price' => 10,
        //     'quantity' => '100',
        //     'discount1' => 1,
        //     'discount2' => 1,
        //     'discount3' => 1,
        //     'discount4' => 1,
        //     'is_shown' => 1
        // ]);

        // DB::table('products')->insert([
        //     'product_category_id'  =>  '3',
        //     'product_type_id'  =>  '9',
        //     'profit_id'  =>  '1',
        //     'main_supplier_id'  =>  '1',
        //     'main_supplier_product_id'  =>  'Bult 14x1.25 Hex 17',
        //     'product_code' =>   'Bultar',
        //     'product_name' =>   'Bult 14x1.25 Hex 17',
        //     'product_description' => '',
        //     'product_dimension' => '14x1.25 Hex 17',
        //     'price' => 10,
        //     'quantity' => '100',
        //     'discount1' => 1,
        //     'discount2' => 1,
        //     'discount3' => 1,
        //     'discount4' => 1,
        //     'is_shown' => 1
        // ]);

        // DB::table('products')->insert([
        //     'product_category_id'  =>  '3',
        //     'product_type_id'  =>  '9',
        //     'profit_id'  =>  '1',
        //     'main_supplier_id'  =>  '1',
        //     'main_supplier_product_id'  =>  'Bult 14x1.5 Hex 17',
        //     'product_code' =>   'Bultar',
        //     'product_name' =>   'Bult 14x1.5 Hex 17',
        //     'product_description' => '',
        //     'product_dimension' => '14x1.5 Hex 17',
        //     'price' => 10,
        //     'quantity' => '100',
        //     'discount1' => 1,
        //     'discount2' => 1,
        //     'discount3' => 1,
        //     'discount4' => 1,
        //     'is_shown' => 1
        // ]);

        // DB::table('products')->insert([
        //     'product_category_id'  =>  '3',
        //     'product_type_id'  =>  '8',
        //     'profit_id'  =>  '1',
        //     'main_supplier_id'  =>  '1',
        //     'main_supplier_product_id'  =>  'Mutter 12x1.25 Hex 17',
        //     'product_code' =>   'Muttrar',
        //     'product_name' =>   'Mutter 12x1.25 Hex 17',
        //     'product_description' => '',
        //     'product_dimension' => '12x1.25 Hex 17',
        //     'price' => 10,
        //     'quantity' => '100',
        //     'discount1' => 1,
        //     'discount2' => 1,
        //     'discount3' => 1,
        //     'discount4' => 1,
        //     'is_shown' => 1
        // ]);

        // DB::table('products')->insert([
        //     'product_category_id'  =>  '3',
        //     'product_type_id'  =>  '8',
        //     'profit_id'  =>  '1',
        //     'main_supplier_id'  =>  '1',
        //     'main_supplier_product_id'  =>  'Mutter 12x1.5 Hex 17',
        //     'product_code' =>   'Muttrar',
        //     'product_name' =>   'Mutter 12x1.5 Hex 17',
        //     'product_description' => '',
        //     'product_dimension' => '12x1.5 Hex 17',
        //     'price' => 10,
        //     'quantity' => '100',
        //     'discount1' => 1,
        //     'discount2' => 1,
        //     'discount3' => 1,
        //     'discount4' => 1,
        //     'is_shown' => 1
        // ]);

        // DB::table('products')->insert([
        //     'product_category_id'  =>  '3',
        //     'product_type_id'  =>  '8',
        //     'profit_id'  =>  '1',
        //     'main_supplier_id'  =>  '1',
        //     'main_supplier_product_id'  =>  'Mutter 14x1.5 Torx',
        //     'product_code' =>   'Muttrar',
        //     'product_name' =>   'Mutter 14x1.5 Torx',
        //     'product_description' => '',
        //     'product_dimension' => '14x1.5 Torx',
        //     'price' => 20,
        //     'quantity' => '100',
        //     'discount1' => 1,
        //     'discount2' => 1,
        //     'discount3' => 1,
        //     'discount4' => 1,
        //     'is_shown' => 1
        // ]);

        // DB::table('products')->insert([
        //     'product_category_id'  =>  '3',
        //     'product_type_id'  =>  '8',
        //     'profit_id'  =>  '1',
        //     'main_supplier_id'  =>  '1',
        //     'main_supplier_product_id'  =>  'Special',
        //     'product_code' =>   'Muttrar',
        //     'product_name' =>   'Special',
        //     'product_description' => '',
        //     'product_dimension' => 'Special',
        //     'price' => 20,
        //     'quantity' => '100',
        //     'discount1' => 1,
        //     'discount2' => 1,
        //     'discount3' => 1,
        //     'discount4' => 1,
        //     'is_shown' => 1
        // ]);

        // DB::table('products')->insert([
        //     'product_category_id'  =>  '3',
        //     'product_type_id'  =>  '10',
        //     'profit_id'  =>  '1',
        //     'main_supplier_id'  =>  '1',
        //     'main_supplier_product_id'  =>  'Hub Ring 76.9-74.1',
        //     'product_code' =>   'Hub Ring 76.9-74.1',
        //     'product_name' =>   'Hub Ring 76.9-74.1',
        //     'product_description' => 'Hub Ring 76.9-74.1',
        //     'product_dimension' => '76.9-74.1',
        //     'price' => 32,
        //     'quantity' => '100',
        //     'discount1' => 1,
        //     'discount2' => 1,
        //     'discount3' => 1,
        //     'discount4' => 1,
        //     'is_shown' => 1
        // ]);

        // DB::table('products')->insert([
        //     'product_category_id'  =>  '3',
        //     'product_type_id'  =>  '10',
        //     'profit_id'  =>  '1',
        //     'main_supplier_id'  =>  '1',
        //     'main_supplier_product_id'  =>  'Hub Ring 74.1-64.1',
        //     'product_code' =>   'Hub Ring 74.1-64.1',
        //     'product_name' =>   'Hub Ring 74.1-64.1',
        //     'product_description' => 'Hub Ring 74.1-64.1',
        //     'product_dimension' => '74.1-64.1',
        //     'price' => 32,
        //     'quantity' => '100',
        //     'discount1' => 1,
        //     'discount2' => 1,
        //     'discount3' => 1,
        //     'discount4' => 1,
        //     'is_shown' => 1
        // ]);

        // DB::table('products')->insert([
        //     'product_category_id'  =>  '3',
        //     'product_type_id'  =>  '10',
        //     'profit_id'  =>  '1',
        //     'main_supplier_id'  =>  '1',
        //     'main_supplier_product_id'  =>  'Hub Ring 74.1-71.1',
        //     'product_code' =>   'Hub Ring 74.1-71.1',
        //     'product_name' =>   'Hub Ring 74.1-71.1',
        //     'product_description' => 'Hub Ring 74.1-71.1',
        //     'product_dimension' => '74.1-71.1',
        //     'price' => 32,
        //     'quantity' => '100',
        //     'discount1' => 1,
        //     'discount2' => 1,
        //     'discount3' => 1,
        //     'discount4' => 1,
        //     'is_shown' => 1
        // ]);

        // DB::table('products')->insert([
        //     'product_category_id'  =>  '3',
        //     'product_type_id'  =>  '10',
        //     'profit_id'  =>  '1',
        //     'main_supplier_id'  =>  '1',
        //     'main_supplier_product_id'  =>  'Hub Ring 67.1-64.1',
        //     'product_code' =>   'Hub Ring 67.1-64.1',
        //     'product_name' =>   'Hub Ring 67.1-64.1',
        //     'product_description' => 'Hub Ring 67.1-64.1',
        //     'product_dimension' => '67.1-64.1',
        //     'price' => 32,
        //     'quantity' => '100',
        //     'discount1' => 1,
        //     'discount2' => 1,
        //     'discount3' => 1,
        //     'discount4' => 1,
        //     'is_shown' => 1
        // ]);

        // DB::table('products')->insert([
        //     'product_category_id'  =>  '3',
        //     'product_type_id'  =>  '10',
        //     'profit_id'  =>  '1',
        //     'main_supplier_id'  =>  '1',
        //     'main_supplier_product_id'  =>  'Hub Ring 67.1-63.4',
        //     'product_code' =>   'Hub Ring 67.1-63.4',
        //     'product_name' =>   'Hub Ring 67.1-63.4',
        //     'product_description' => 'Hub Ring 67.1-63.4',
        //     'product_dimension' => '67.1-63.4',
        //     'price' => 32,
        //     'quantity' => '100',
        //     'discount1' => 1,
        //     'discount2' => 1,
        //     'discount3' => 1,
        //     'discount4' => 1,
        //     'is_shown' => 1
        // ]);

        // DB::table('products')->insert([
        //     'product_category_id'  =>  '3',
        //     'product_type_id'  =>  '10',
        //     'profit_id'  =>  '1',
        //     'main_supplier_id'  =>  '1',
        //     'main_supplier_product_id'  =>  'Hub Ring 76.9-72.6',
        //     'product_code' =>   'Hub Ring 76.9-72.6',
        //     'product_name' =>   'Hub Ring 76.9-72.6',
        //     'product_description' => 'Hub Ring 76.9-72.6',
        //     'product_dimension' => '76.9-72.6',
        //     'price' => 32,
        //     'quantity' => '100',
        //     'discount1' => 1,
        //     'discount2' => 1,
        //     'discount3' => 1,
        //     'discount4' => 1,
        //     'is_shown' => 1
        // ]);

        // DB::table('products')->insert([
        //     'product_category_id'  =>  '3',
        //     'product_type_id'  =>  '10',
        //     'profit_id'  =>  '1',
        //     'main_supplier_id'  =>  '1',
        //     'main_supplier_product_id'  =>  'Hub Ring 74.1-66.1',
        //     'product_code' =>   'Hub Ring 74.1-66.1',
        //     'product_name' =>   'Hub Ring 74.1-66.1',
        //     'product_description' => 'Hub Ring 74.1-66.1',
        //     'product_dimension' => '74.1-66.1',
        //     'price' => 32,
        //     'quantity' => '100',
        //     'discount1' => 1,
        //     'discount2' => 1,
        //     'discount3' => 1,
        //     'discount4' => 1,
        //     'is_shown' => 1
        // ]);

        // DB::table('products')->insert([
        //     'product_category_id'  =>  '3',
        //     'product_type_id'  =>  '10',
        //     'profit_id'  =>  '1',
        //     'main_supplier_id'  =>  '1',
        //     'main_supplier_product_id'  =>  'Hub Ring 73.1-66.5',
        //     'product_code' =>   'Hub Ring 73.1-66.5',
        //     'product_name' =>   'Hub Ring 73.1-66.5',
        //     'product_description' => 'Hub Ring 73.1-66.5',
        //     'product_dimension' => '73.1-66.5',
        //     'price' => 32,
        //     'quantity' => '100',
        //     'discount1' => 1,
        //     'discount2' => 1,
        //     'discount3' => 1,
        //     'discount4' => 1,
        //     'is_shown' => 1
        // ]);

        // DB::table('products')->insert([
        //     'product_category_id'  =>  '3',
        //     'product_type_id'  =>  '10',
        //     'profit_id'  =>  '1',
        //     'main_supplier_id'  =>  '1',
        //     'main_supplier_product_id'  =>  'Hub Ring 74.1-65.1',
        //     'product_code' =>   'Hub Ring 74.1-65.1',
        //     'product_name' =>   'Hub Ring 74.1-65.1',
        //     'product_description' => 'Hub Ring 74.1-65.1',
        //     'product_dimension' => '74.1-65.1',
        //     'price' => 32,
        //     'quantity' => '100',
        //     'discount1' => 1,
        //     'discount2' => 1,
        //     'discount3' => 1,
        //     'discount4' => 1,
        //     'is_shown' => 1
        // ]);

        // DB::table('products')->insert([
        //     'product_category_id'  =>  '3',
        //     'product_type_id'  =>  '10',
        //     'profit_id'  =>  '1',
        //     'main_supplier_id'  =>  '1',
        //     'main_supplier_product_id'  =>  'Hub Ring 74.1-60.1',
        //     'product_code' =>   'Hub Ring 74.1-60.1',
        //     'product_name' =>   'Hub Ring 74.1-60.1',
        //     'product_description' => 'Hub Ring 74.1-60.1',
        //     'product_dimension' => '74.1-60.1',
        //     'price' => 32,
        //     'quantity' => '100',
        //     'discount1' => 1,
        //     'discount2' => 1,
        //     'discount3' => 1,
        //     'discount4' => 1,
        //     'is_shown' => 1
        // ]);

        // DB::table('products')->insert([
        //     'product_category_id'  =>  '3',
        //     'product_type_id'  =>  '10',
        //     'profit_id'  =>  '1',
        //     'main_supplier_id'  =>  '1',
        //     'main_supplier_product_id'  =>  'Hub Ring 74.1-70.1',
        //     'product_code' =>   'Hub Ring 74.1-70.1',
        //     'product_name' =>   'Hub Ring 74.1-70.1',
        //     'product_description' => 'Hub Ring 74.1-70.1',
        //     'product_dimension' => '74.1-70.1',
        //     'price' => 32,
        //     'quantity' => '100',
        //     'discount1' => 1,
        //     'discount2' => 1,
        //     'discount3' => 1,
        //     'discount4' => 1,
        //     'is_shown' => 1
        // ]);

        // DB::table('products')->insert([
        //     'product_category_id'  =>  '3',
        //     'product_type_id'  =>  '10',
        //     'profit_id'  =>  '1',
        //     'main_supplier_id'  =>  '1',
        //     'main_supplier_product_id'  =>  'Hub Ring 74.1-66.5',
        //     'product_code' =>   'Hub Ring 74.1-66.5',
        //     'product_name' =>   'Hub Ring 74.1-66.5',
        //     'product_description' => 'Hub Ring 74.1-66.5',
        //     'product_dimension' => '74.1-66.5',
        //     'price' => 32,
        //     'quantity' => '100',
        //     'discount1' => 1,
        //     'discount2' => 1,
        //     'discount3' => 1,
        //     'discount4' => 1,
        //     'is_shown' => 1
        // ]);

        // DB::table('products')->insert([
        //     'product_category_id'  =>  '3',
        //     'product_type_id'  =>  '10',
        //     'profit_id'  =>  '1',
        //     'main_supplier_id'  =>  '1',
        //     'main_supplier_product_id'  =>  'Hub Ring 74.1-66.6',
        //     'product_code' =>   'Hub Ring 74.1-66.6',
        //     'product_name' =>   'Hub Ring 74.1-66.6',
        //     'product_description' => 'Hub Ring 74.1-66.6',
        //     'product_dimension' => '74.1-66.6',
        //     'price' => 32,
        //     'quantity' => '100',
        //     'discount1' => 1,
        //     'discount2' => 1,
        //     'discount3' => 1,
        //     'discount4' => 1,
        //     'is_shown' => 1
        // ]);

        // DB::table('products')->insert([
        //     'product_category_id'  =>  '3',
        //     'product_type_id'  =>  '10',
        //     'profit_id'  =>  '1',
        //     'main_supplier_id'  =>  '1',
        //     'main_supplier_product_id'  =>  'Hub Ring 67.1-65.1',
        //     'product_code' =>   'Hub Ring 67.1-65.1',
        //     'product_name' =>   'Hub Ring 67.1-65.1',
        //     'product_description' => 'Hub Ring 67.1-65.1',
        //     'product_dimension' => '67.1-65.1',
        //     'price' => 32,
        //     'quantity' => '100',
        //     'discount1' => 1,
        //     'discount2' => 1,
        //     'discount3' => 1,
        //     'discount4' => 1,
        //     'is_shown' => 1
        // ]);

        // DB::table('products')->insert([
        //     'product_category_id'  =>  '3',
        //     'product_type_id'  =>  '10',
        //     'profit_id'  =>  '1',
        //     'main_supplier_id'  =>  '1',
        //     'main_supplier_product_id'  =>  'Hub Ring 74.1-67.1',
        //     'product_code' =>   'Hub Ring 74.1-67.1',
        //     'product_name' =>   'Hub Ring 74.1-67.1',
        //     'product_description' => 'Hub Ring 74.1-67.1',
        //     'product_dimension' => '74.1-67.1',
        //     'price' => 32,
        //     'quantity' => '100',
        //     'discount1' => 1,
        //     'discount2' => 1,
        //     'discount3' => 1,
        //     'discount4' => 1,
        //     'is_shown' => 1
        // ]);

        // DB::table('products')->insert([
        //     'product_category_id'  =>  '3',
        //     'product_type_id'  =>  '10',
        //     'profit_id'  =>  '1',
        //     'main_supplier_id'  =>  '1',
        //     'main_supplier_product_id'  =>  'Hub Ring 74.1-56.6',
        //     'product_code' =>   'Hub Ring 74.1-56.6',
        //     'product_name' =>   'Hub Ring 74.1-56.6',
        //     'product_description' => 'Hub Ring 74.1-56.6',
        //     'product_dimension' => '74.1-56.6',
        //     'price' => 32,
        //     'quantity' => '100',
        //     'discount1' => 1,
        //     'discount2' => 1,
        //     'discount3' => 1,
        //     'discount4' => 1,
        //     'is_shown' => 1
        // ]);

        // DB::table('products')->insert([
        //     'product_category_id'  =>  '3',
        //     'product_type_id'  =>  '10',
        //     'profit_id'  =>  '1',
        //     'main_supplier_id'  =>  '1',
        //     'main_supplier_product_id'  =>  'Hub Ring 74.1-56.1',
        //     'product_code' =>   'Hub Ring 74.1-56.1',
        //     'product_name' =>   'Hub Ring 74.1-56.1',
        //     'product_description' => 'Hub Ring 74.1-56.1',
        //     'product_dimension' => '74.1-56.1',
        //     'price' => 32,
        //     'quantity' => '100',
        //     'discount1' => 1,
        //     'discount2' => 1,
        //     'discount3' => 1,
        //     'discount4' => 1,
        //     'is_shown' => 1
        // ]);

        // DB::table('products')->insert([
        //     'product_category_id'  =>  '3',
        //     'product_type_id'  =>  '10',
        //     'profit_id'  =>  '1',
        //     'main_supplier_id'  =>  '1',
        //     'main_supplier_product_id'  =>  'Hub Ring 74.1-63.4',
        //     'product_code' =>   'Hub Ring 74.1-63.4',
        //     'product_name' =>   'Hub Ring 74.1-63.4',
        //     'product_description' => 'Hub Ring 74.1-63.4',
        //     'product_dimension' => '74.1-63.4',
        //     'price' => 32,
        //     'quantity' => '100',
        //     'discount1' => 1,
        //     'discount2' => 1,
        //     'discount3' => 1,
        //     'discount4' => 1,
        //     'is_shown' => 1
        // ]);

        // DB::table('products')->insert([
        //     'product_category_id'  =>  '3',
        //     'product_type_id'  =>  '10',
        //     'profit_id'  =>  '1',
        //     'main_supplier_id'  =>  '1',
        //     'main_supplier_product_id'  =>  'Hub Ring 74.1-57.1',
        //     'product_code' =>   'Hub Ring 74.1-57.1',
        //     'product_name' =>   'Hub Ring 74.1-57.1',
        //     'product_description' => 'Hub Ring 74.1-57.1',
        //     'product_dimension' => '74.1-57.1',
        //     'price' => 32,
        //     'quantity' => '100',
        //     'discount1' => 1,
        //     'discount2' => 1,
        //     'discount3' => 1,
        //     'discount4' => 1,
        //     'is_shown' => 1
        // ]);

        // DB::table('products')->insert([
        //     'product_category_id'  =>  '3',
        //     'product_type_id'  =>  '10',
        //     'profit_id'  =>  '1',
        //     'main_supplier_id'  =>  '1',
        //     'main_supplier_product_id'  =>  'Hub Ring 73.1-63.4',
        //     'product_code' =>   'Hub Ring 73.1-63.4',
        //     'product_name' =>   'Hub Ring 73.1-63.4',
        //     'product_description' => 'Hub Ring 73.1-63.4',
        //     'product_dimension' => '73.1-63.4',
        //     'price' => 32,
        //     'quantity' => '100',
        //     'discount1' => 1,
        //     'discount2' => 1,
        //     'discount3' => 1,
        //     'discount4' => 1,
        //     'is_shown' => 1
        // ]);

        // DB::table('products')->insert([
        //     'product_category_id'  =>  '3',
        //     'product_type_id'  =>  '10',
        //     'profit_id'  =>  '1',
        //     'main_supplier_id'  =>  '1',
        //     'main_supplier_product_id'  =>  'Hub Ring 65.1-63.4',
        //     'product_code' =>   'Hub Ring 65.1-63.4',
        //     'product_name' =>   'Hub Ring 65.1-63.4',
        //     'product_description' => 'Hub Ring 65.1-63.4',
        //     'product_dimension' => '65.1-63.4',
        //     'price' => 32,
        //     'quantity' => '100',
        //     'discount1' => 1,
        //     'discount2' => 1,
        //     'discount3' => 1,
        //     'discount4' => 1,
        //     'is_shown' => 1
        // ]);

        // DB::table('products')->insert([
        //     'product_category_id'  =>  '3',
        //     'product_type_id'  =>  '10',
        //     'profit_id'  =>  '1',
        //     'main_supplier_id'  =>  '1',
        //     'main_supplier_product_id'  =>  'Hub Ring 73.1-57.1',
        //     'product_code' =>   'Hub Ring 73.1-57.1',
        //     'product_name' =>   'Hub Ring 73.1-57.1',
        //     'product_description' => 'Hub Ring 73.1-57.1',
        //     'product_dimension' => '73.1-57.1',
        //     'price' => 32,
        //     'quantity' => '100',
        //     'discount1' => 1,
        //     'discount2' => 1,
        //     'discount3' => 1,
        //     'discount4' => 1,
        //     'is_shown' => 1
        // ]);

        // DB::table('products')->insert([
        //     'product_category_id'  =>  '3',
        //     'product_type_id'  =>  '10',
        //     'profit_id'  =>  '1',
        //     'main_supplier_id'  =>  '1',
        //     'main_supplier_product_id'  =>  'Hub Ring 73.1-65.1',
        //     'product_code' =>   'Hub Ring 73.1-65.1',
        //     'product_name' =>   'Hub Ring 73.1-65.1',
        //     'product_description' => 'Hub Ring 73.1-65.1',
        //     'product_dimension' => '73.1-65.1',
        //     'price' => 32,
        //     'quantity' => '100',
        //     'discount1' => 1,
        //     'discount2' => 1,
        //     'discount3' => 1,
        //     'discount4' => 1,
        //     'is_shown' => 1
        // ]);

        // DB::table('products')->insert([
        //     'product_category_id'  =>  '3',
        //     'product_type_id'  =>  '10',
        //     'profit_id'  =>  '1',
        //     'main_supplier_id'  =>  '1',
        //     'main_supplier_product_id'  =>  'Hub Ring 73.1-66.6',
        //     'product_code' =>   'Hub Ring 73.1-66.6',
        //     'product_name' =>   'Hub Ring 73.1-66.6',
        //     'product_description' => 'Hub Ring 73.1-66.6',
        //     'product_dimension' => '73.1-66.6',
        //     'price' => 32,
        //     'quantity' => '100',
        //     'discount1' => 1,
        //     'discount2' => 1,
        //     'discount3' => 1,
        //     'discount4' => 1,
        //     'is_shown' => 1
        // ]);

        // DB::table('products')->insert([
        //     'product_category_id'  =>  '3',
        //     'product_type_id'  =>  '10',
        //     'profit_id'  =>  '1',
        //     'main_supplier_id'  =>  '1',
        //     'main_supplier_product_id'  =>  'Hub Ring 73.1-66.5',
        //     'product_code' =>   'Hub Ring 73.1-66.5',
        //     'product_name' =>   'Hub Ring 73.1-66.5',
        //     'product_description' => 'Hub Ring 73.1-66.5',
        //     'product_dimension' => '73.1-66.5',
        //     'price' => 32,
        //     'quantity' => '100',
        //     'discount1' => 1,
        //     'discount2' => 1,
        //     'discount3' => 1,
        //     'discount4' => 1,
        //     'is_shown' => 1
        // ]);

        // DB::table('products')->insert([
        //     'product_category_id'  =>  '3',
        //     'product_type_id'  =>  '10',
        //     'profit_id'  =>  '1',
        //     'main_supplier_id'  =>  '1',
        //     'main_supplier_product_id'  =>  'Hub Ring 73.1-67.1',
        //     'product_code' =>   'Hub Ring 73.1-67.1',
        //     'product_name' =>   'Hub Ring 73.1-67.1',
        //     'product_description' => 'Hub Ring 73.1-67.1',
        //     'product_dimension' => '73.1-67.1',
        //     'price' => 32,
        //     'quantity' => '100',
        //     'discount1' => 1,
        //     'discount2' => 1,
        //     'discount3' => 1,
        //     'discount4' => 1,
        //     'is_shown' => 1
        // ]);

        // DB::table('products')->insert([
        //     'product_category_id'  =>  '3',
        //     'product_type_id'  =>  '13',
        //     'profit_id'  =>  '1',
        //     'main_supplier_id'  =>  '1',
        //     'main_supplier_product_id'  =>  'Spacer 5mm',
        //     'product_code' =>   'Spacers',
        //     'product_name' =>   'Spacer 5mm',
        //     'product_description' => '',
        //     'product_dimension' => '5mm',
        //     'price' => 120,
        //     'quantity' => '100',
        //     'discount1' => 1,
        //     'discount2' => 1,
        //     'discount3' => 1,
        //     'discount4' => 1,
        //     'is_shown' => 1
        // ]);

        // DB::table('products')->insert([
        //     'product_category_id'  =>  '3',
        //     'product_type_id'  =>  '13',
        //     'profit_id'  =>  '1',
        //     'main_supplier_id'  =>  '1',
        //     'main_supplier_product_id'  =>  'Spacer 10mm',
        //     'product_code' =>   'Spacers',
        //     'product_name' =>   'Spacer 10mm',
        //     'product_description' => '',
        //     'product_dimension' => '10mm',
        //     'price' => 150,
        //     'quantity' => '100',
        //     'discount1' => 1,
        //     'discount2' => 1,
        //     'discount3' => 1,
        //     'discount4' => 1,
        //     'is_shown' => 1
        // ]);

        // DB::table('products')->insert([
        //     'product_category_id'  =>  '3',
        //     'product_type_id'  =>  '13',
        //     'profit_id'  =>  '1',
        //     'main_supplier_id'  =>  '1',
        //     'main_supplier_product_id'  =>  'Spacer 15mm',
        //     'product_code' =>   'Spacers',
        //     'product_name' =>   'Spacer 15mm',
        //     'product_description' => '',
        //     'product_dimension' => '15mm',
        //     'price' => 180,
        //     'quantity' => '100',
        //     'discount1' => 1,
        //     'discount2' => 1,
        //     'discount3' => 1,
        //     'discount4' => 1,
        //     'is_shown' => 1
        // ]);

        // DB::table('products')->insert([
        //     'product_category_id'  =>  '3',
        //     'product_type_id'  =>  '13',
        //     'profit_id'  =>  '1',
        //     'main_supplier_id'  =>  '1',
        //     'main_supplier_product_id'  =>  'Spacer 20mm',
        //     'product_code' =>   'Spacers',
        //     'product_name' =>   'Spacer 20mm',
        //     'product_description' => '',
        //     'product_dimension' => '20mm',
        //     'price' => 220,
        //     'quantity' => '100',
        //     'discount1' => 1,
        //     'discount2' => 1,
        //     'discount3' => 1,
        //     'discount4' => 1,
        //     'is_shown' => 1
        // ]);

        // DB::table('products')->insert([
        //     'product_category_id'  =>  '3',
        //     'product_type_id'  =>  '13',
        //     'profit_id'  =>  '1',
        //     'main_supplier_id'  =>  '1',
        //     'main_supplier_product_id'  =>  'Spacer Special',
        //     'product_code' =>   'Spacers',
        //     'product_name' =>   'Spacer Special',
        //     'product_description' => '',
        //     'product_dimension' => 'Special',
        //     'price' => 250,
        //     'quantity' => '100',
        //     'discount1' => 1,
        //     'discount2' => 1,
        //     'discount3' => 1,
        //     'discount4' => 1,
        //     'is_shown' => 1
        // ]);

        // DB::table('products')->insert([
        //     'product_category_id'  =>  '3',
        //     'product_type_id'  =>  '13',
        //     'profit_id'  =>  '1',
        //     'main_supplier_id'  =>  '1',
        //     'main_supplier_product_id'  =>  'Bolton Spacer',
        //     'product_code' =>   'Spacers',
        //     'product_name' =>   'Bolton Spacer',
        //     'product_description' => '',
        //     'product_dimension' => 'Bolton Spacer',
        //     'price' => 400,
        //     'quantity' => '100',
        //     'discount1' => 1,
        //     'discount2' => 1,
        //     'discount3' => 1,
        //     'discount4' => 1,
        //     'is_shown' => 1
        // ]);
    }
}
