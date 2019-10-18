<?php

use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('settings')->delete();

        DB::table('settings')->insert([
            'settings_type'  =>  1,
            'name'  =>  'blog_page',
            'label' =>  'Välj bloggsida:',
            'value' =>  0,
        ]);

        DB::table('settings')->insert([
            'settings_type'  =>  1,
            'name'  =>  'phone',
            'label' =>  'Telefon:',
        ]);

        DB::table('settings')->insert([
            'settings_type'  =>  1,
            'name'  =>  'support_mail',
            'label' =>  'Support E-post:',
        ]);

        DB::table('settings')->insert([
            'settings_type'  =>  1,
            'name'  =>  'order_mail',
            'label' =>  'Order E-post:',
        ]);

        DB::table('settings')->insert([
            'settings_type'  =>  1,
            'name'  =>  'street_address',
            'label' => 'Gata:',
        ]);

        DB::table('settings')->insert([
            'settings_type'  =>  1,
            'name'  =>  'city',
            'label' =>  'Stad:',
        ]);

        DB::table('settings')->insert([
            'settings_type'  =>  1,
            'name'  =>  'postal_code',
            'label' =>  'Postnummer:',
        ]);

        DB::table('settings')->insert([
            'settings_type'  =>  2,
            'name'  =>  'bank_giro',
            'label' =>  'Bankgiro:',
        ]);

        DB::table('settings')->insert([
            'settings_type'  =>  2,
            'name'  =>  'org_number',
            'label' =>  'Organisations nr:',
        ]);

        DB::table('settings')->insert([
            'settings_type'  =>  2,
            'name'  =>  'vat_number',
            'label' =>  'Momsregistrering nr:',
        ]);

        DB::table('settings')->insert([
            'settings_type'  =>  3,
            'name'  =>  'facebook',
            'label' =>  'Facebook url:',
        ]);

        DB::table('settings')->insert([
            'settings_type'  =>  3,
            'name'  =>  'instagram',
            'label' =>  'Instagram url:',
        ]);

        DB::table('settings')->insert([
            'settings_type'  =>  3,
            'name'  =>  'twitter',
            'label' =>  'Twitter url:',
        ]);

        DB::table('settings')->insert([
            'settings_type'  =>  3,
            'name'  =>  'youtube',
            'label' =>  'Youtube url:',
        ]);

        DB::table('settings')->insert([
            'settings_type'  =>  3,
            'name'  =>  'google_plus',
            'label' =>  'Google plus url:',
        ]);
    }
}
