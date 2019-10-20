<?php

use Illuminate\Database\Seeder;

class ProductTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('product_type')->delete();
  
        DB::table('product_type')->insert([
            'name'  =>  'sommardack',
            'label' =>	'Sommardäck',
        ]);

        DB::table('product_type')->insert([
            'name'  =>  'friktionsdack',
            'label' =>	'Friktionsdäck',
        ]);

        DB::table('product_type')->insert([
            'name'  =>  'dubbdack',
            'label' =>	'Dubbdäck',
        ]);

        DB::table('product_type')->insert([
            'name'  =>  'falgar',
            'label' =>	'Fälgar',
        ]);

        DB::table('product_type')->insert([
            'name'  =>  'stalfalgar',
            'label' =>	'Stålfälg',
        ]);

        DB::table('product_type')->insert([
            'name'  =>  'platfalgar',
            'label' =>	'Plålfälg',
        ]);

        DB::table('product_type')->insert([
            'name'  =>  'tpms',
            'label' =>  'TPMS',
        ]);

        DB::table('product_type')->insert([
            'name'  =>  'muttrar',
            'label' =>  'Muttrar',
        ]);

        DB::table('product_type')->insert([
            'name'  =>  'bultar',
            'label' =>  'Bultar',
        ]);

        DB::table('product_type')->insert([
            'name'  =>  'ringar',
            'label' =>  'Ringar',
        ]);

        DB::table('product_type')->insert([
            'name'  =>  'monteringskit',
            'label' =>  'Monteringskit',
        ]);

        DB::table('product_type')->insert([
            'name'  =>  'lasbultar',
            'label' =>  'Låsbultar',
        ]);

        DB::table('product_type')->insert([
            'name'  =>  'spacers',
            'label' =>  'Spacers',
        ]);

        DB::table('product_type')->insert([
            'name'  =>  'tjanster',
            'label' =>  'Tjänster',
        ]);

        DB::table('product_type')->insert([
            'name'  =>  'mont_balans',
            'label' =>  'Montering & balansering',
        ]);

        DB::table('product_type')->insert([
            'name'  =>  'special_bult_mutter',
            'label' =>  'Special bult / mutter',
        ]);

        DB::table('product_type')->insert([
            'name'  =>  'tackkapor',
            'label' =>  'Täckkåpor till bult / mutter',
        ]);

        // DB::table('product_type')->insert([
        //     'name'  =>  'monteringskit',
        //     'label' =>  'Monteringskit',
        // ]);
    }
}
