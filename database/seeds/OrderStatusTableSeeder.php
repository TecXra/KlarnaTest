<?php

use Illuminate\Database\Seeder;

class OrderStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('order_status')->delete();

        DB::table('order_status')->insert([
            'name' =>   'ny order',
            'label' =>  'Ny order',
        ]);
  
        DB::table('order_status')->insert([
            'name' =>	'behandlas',
            'label' =>	'Behandlas',
        ]);

        DB::table('order_status')->insert([
            'name' =>	'avslag',
            'label' =>	'Avslag',
        ]);

        DB::table('order_status')->insert([
            'name' =>	'skickat',
            'label' =>	'Skickat',
        ]);
    }
}
