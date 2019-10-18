<?php

use Illuminate\Database\Seeder;

class UserTypeTableSeeder extends Seeder
{
   /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_type')->delete();

        DB::table('user_type')->insert([
            'name'  =>  'level1',
            'label' =>	'Nivå 1',
        ]);

        DB::table('user_type')->insert([
            'name'  =>  'level2',
            'label' =>  'Nivå 2',
        ]);

        DB::table('user_type')->insert([
            'name'  =>  'level3',
            'label' =>  'Nivå 3',
        ]);

        DB::table('user_type')->insert([
            'name'  =>  'level4',
            'label' =>  'Nivå 4',
        ]);

        // när detta körs måste Auth::user()->id == ändras från 3 till 5
        DB::table('user_type')->insert([
            'name'  =>  'admin',
            'label' =>	'Admin',
        ]);
    }
}
