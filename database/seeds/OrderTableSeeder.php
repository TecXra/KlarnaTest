<?php

use Illuminate\Database\Seeder;

class OrderTableSeeder extends Seeder
{
	/**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$dt = new DateTime;
        DB::table('orders')->delete();
  
        DB::table('orders')->insert([
            'user_id' 	=>	1,
            'order_number' 	=>	1263125,
            'total_price_excluding_tax' =>	5435,
            'total_price_including_tax' =>	5670,
            'total_tax_amount' =>	235,
            'order_date' =>	$dt->format('m-d-y H:i:s'),
            'billing_street_address' =>	'Min adress 123',
            'billing_postal_code' => '12345',
            'billing_city' => 'Stockholm',
            'billing_country' => 'SE',
            'shipping_street_address' =>	'Min adress 123',
            'shipping_postal_code' => '12345',
            'shipping_city' => 'Stockholm',
            'shipping_country' => 'SE',
        ]);

        DB::table('orders')->insert([
            'user_id' 	=>	1,
            'order_number' 	=>	1363125,
            'total_price_excluding_tax' =>	4435,
            'total_price_including_tax' =>	4670,
            'total_tax_amount' =>	295,
            'order_date' =>	$dt->format('m-d-y H:i:s'),
            'billing_street_address' =>	'Min gata 321',
            'billing_postal_code' => '56789',
            'billing_city' => 'Stockholm',
            'billing_country' => 'SE',
            'shipping_street_address' =>	'Min gata 321',
            'shipping_postal_code' => '56789',
            'shipping_city' => 'Stockholm',
            'shipping_country' => 'SE',
        ]);

        DB::table('orders')->insert([
            'user_id' 	=>	1,
            'order_number' 	=>	1423425,
            'total_price_excluding_tax' =>	3435,
            'total_price_including_tax' =>	3790,
            'total_tax_amount' =>	395,
            'order_date' =>	$dt->format('m-d-y H:i:s'),
            'billing_street_address' =>	'Gatan 234',
            'billing_postal_code' => '23456',
            'billing_city' => 'Stockholm',
            'billing_country' => 'SE',
            'shipping_street_address' =>	'Gatan 234',
            'shipping_postal_code' => '23456',
            'shipping_city' => 'Stockholm',
            'shipping_country' => 'SE',
        ]);

    }
}
