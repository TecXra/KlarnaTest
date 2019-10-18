<?php

use Illuminate\Database\Seeder;

class PagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pages')->delete();

        DB::table('pages')->insert([
            'name'  =>  '/',
            'label' =>	'Kompletta hjul',
            'is_post'  =>  0,
            'is_active'  =>  1,
            'is_removable' => 0,
            'created_at' =>	date("Y-m-d H:i:s"),
            'updated_at'  =>  date("Y-m-d H:i:s"),
        ]);
        DB::table('pages')->insert([
            'name'  =>  'falgar',
            'label' =>	'Fälgar',
            'is_post'  =>  0,
            'is_active'  =>  1,
            'is_removable' => 0,
            'created_at' =>	date("Y-m-d H:i:s"),
            'updated_at'  =>  date("Y-m-d H:i:s"),
        ]);
        DB::table('pages')->insert([
            'name'  =>  'sommardack',
            'label' =>	'Sommardäck',
            'is_post'  =>  0,
            'is_active'  =>  1,
            'is_removable' => 0,
            'created_at' =>	date("Y-m-d H:i:s"),
            'updated_at'  =>  date("Y-m-d H:i:s"),
        ]);
        DB::table('pages')->insert([
            'name'  =>  'friktionsdack',
            'label' =>	'Friktionsdäck',
            'is_post'  =>  0,
            'is_active'  =>  1,
            'is_removable' => 0,
            'created_at' =>	date("Y-m-d H:i:s"),
            'updated_at'  =>  date("Y-m-d H:i:s"),
        ]);
        DB::table('pages')->insert([
            'name'  =>  'dubbdack',
            'label' =>	'Dubbdäck',
            'is_post'  =>  0,
            'is_active'  =>  1,
            'is_removable' => 0,
            'created_at' =>	date("Y-m-d H:i:s"),
            'updated_at'  =>  date("Y-m-d H:i:s"),
        ]);
        DB::table('pages')->insert([
            'name'  =>  'tillbehor',
            'label' =>	'Tillbehör',
            'is_post'  =>  0,
            'is_active'  =>  1,
            'is_removable' => 0,
            'created_at' =>	date("Y-m-d H:i:s"),
            'updated_at'  =>  date("Y-m-d H:i:s"),
        ]);
        DB::table('pages')->insert([
            'name'  =>  'galleri',
            'label' =>	'Galleri',
            'is_post'  =>  0,
            'is_active'  =>  1,
            'is_removable' => 0,
            'created_at' =>	date("Y-m-d H:i:s"),
            'updated_at'  =>  date("Y-m-d H:i:s"),
        ]);
        DB::table('pages')->insert([
            'name'  =>  'kontakt',
            'label' =>	'Kontakta oss',
            'is_post'  =>  0,
            'is_active'  =>  1,
            'is_removable' => 0,
            'created_at' =>	date("Y-m-d H:i:s"),
            'updated_at'  =>  date("Y-m-d H:i:s"),
        ]);
    }
}
