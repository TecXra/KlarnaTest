<?php

use Illuminate\Database\Seeder;

class MenusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       	DB::table('menus')->delete();

        DB::table('menus')->insert([
            'name'  =>  'Huvvud Meny',
            'created_at' =>	date("Y-m-d H:i:s"),
            'updated_at'  =>  date("Y-m-d H:i:s"),
        ]);

        DB::table('menus')->insert([
            'name'  =>  'Sidfot Meny',
            'created_at' =>	date("Y-m-d H:i:s"),
            'updated_at'  =>  date("Y-m-d H:i:s"),
        ]);
    }
}
