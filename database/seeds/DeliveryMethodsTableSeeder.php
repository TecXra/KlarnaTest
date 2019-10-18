<?php

use Illuminate\Database\Seeder;

class DeliveryMethodsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('delivery_methods')->delete();

        // DB::table('delivery_methods')->insert([
        //     'label'  =>  'Hämtas upp',
        //     'information'  =>  'Hämtas upp / Monteras i verkstad <br> Citydäck i Malmö, Norra Grängesbergsgatan 14.',
        // ]);

        DB::table('delivery_methods')->insert([
            'label'  =>  'Privatperson inrikes leverans',
            'information'  =>  'Bussgods / DHL',
        ]);

        DB::table('delivery_methods')->insert([
            'label'  =>  'Företagspaket inrikes leverans',
            'information'  =>  'Bussgods / DHL',
        ]);
        
    }
}
