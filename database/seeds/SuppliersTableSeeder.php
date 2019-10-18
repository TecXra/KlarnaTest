<?php

use Illuminate\Database\Seeder;

class SuppliersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('suppliers')->delete();

        // For accessories products
        DB::table('suppliers')->insert([
            'company_name'   =>  'Local',
        ]);
        
        DB::table('suppliers')->insert([
            'company_name'   =>  'Inter-sprint tires',
        ]);

        DB::table('suppliers')->insert([
            'company_name'   =>  'Inter-tyre rims',
        ]);

        DB::table('suppliers')->insert([
            'company_name'   =>  'ABS rims',
        ]);

        // DB::table('suppliers')->insert([
        //     'company_name'   =>  'Delticom tires',
        // ]);

        // DB::table('suppliers')->insert([
        //     'company_name'   =>  'Imads products',
        // ]);

       

        //Japan Racing
        // DB::table('suppliers')->insert([
        //     'company_name'   =>  'JR',
        // ]);

        // //GummiGrossen
        // DB::table('suppliers')->insert([
        //     'company_name'   =>  'GG',
        // ]);

        // DB::table('suppliers')->insert([
        //     'company_name'   =>  'RotaWheels',
        // ]);

        // //Fråga vem dessa är
        // DB::table('suppliers')->insert([
        //     'company_name'   =>  'Data',
        // ]);

        // //Fråga vem dessa är
        // DB::table('suppliers')->insert([
        //     'company_name'   =>  'Accessories',
        // ]);
    }
}
