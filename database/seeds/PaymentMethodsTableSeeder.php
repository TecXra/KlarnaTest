<?php

use Illuminate\Database\Seeder;

class PaymentMethodsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('payment_methods')->delete();

        DB::table('payment_methods')->insert([
            'label'  =>  'Kort, Faktura, Delbetalning & Bank',
            'information'  =>  'Välj själv i vilken takt du vill betala',
        ]);

        DB::table('payment_methods')->insert([
            'label'  =>  'Betala i butik',
            'information'  =>  'Med det här alternativet väljer ni att betala era varor med Kort eller Kontant när ni hämtar dem i vårt butik på däckline ????.',
        ]);
    }
}
