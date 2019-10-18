<?php

use Illuminate\Database\Seeder;

class PaymentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('payments')->delete();

        DB::table('payments')->insert([
            'payment_code'  =>  223060,
            'description'  =>  'Köp nu betala om 3 månader (räntefritt)',
            'contract_length_in_months'  =>  3,
            'monthly_annuity_factor'  =>  1,
            'initial_fee'  =>  29,
            'notification_fee'  =>  29,
            'interest_rate_percent' =>  0,
        ]);

        DB::table('payments')->insert([
            'payment_code'  =>  310003,
            'description'  =>  'Dela upp betalningen på 3 månader (räntefritt)',
            'contract_length_in_months'  =>  3,
            'monthly_annuity_factor'  =>  0.333333333333333,
            'initial_fee'  =>  95,
            'notification_fee'  =>  29,
            'interest_rate_percent' =>  0,
        ]);

        DB::table('payments')->insert([
            'payment_code'  =>  310006,
            'description'  =>  'Dela upp betalningen på 6 månader (räntefritt)',
            'contract_length_in_months'  =>  6,
            'monthly_annuity_factor'  =>  0.166666666666667,
            'initial_fee'  =>  195,
            'notification_fee'  =>  29,
            'interest_rate_percent' =>  0,
        ]);

        DB::table('payments')->insert([
            'payment_code'  =>  310012,
            'description'  =>  'Dela upp betalningen på 12 månader (räntefritt)',
            'contract_length_in_months'  =>  12,
            'monthly_annuity_factor'  =>  0.0833333333333333,
            'initial_fee'  =>  295,
            'notification_fee'  =>  29,
            'interest_rate_percent' =>  0,
        ]);

        DB::table('payments')->insert([
            'payment_code'  =>  410024,
            'description'  =>  'Dela upp betalningen på 24 månader',
            'contract_length_in_months'  =>  24,
            'monthly_annuity_factor'  =>  0.0461218523033516,
            'initial_fee'  =>  295,
            'notification_fee'  =>  29,
            'interest_rate_percent' =>  9.95,
        ]);
        
    }
}
