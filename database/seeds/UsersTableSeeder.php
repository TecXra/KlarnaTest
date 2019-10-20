<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();

        DB::table('users')->insert([
            'user_type_id'  =>  5,
            'first_name' => 'Sibar',
            'last_name'  =>  'Al-ani',
            'company_name' =>   'ABS Wheels',
            'email'  =>  'sibar@abswheels.se',
            'password' =>   bcrypt('123123'),
            'is_active'  =>  1,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at'  =>  date("Y-m-d H:i:s"),
        ]);
        
        DB::table('users')->insert([
            'user_type_id'  =>  5,
            'first_name' => 'Sadiq',
            'last_name'  =>  'Malik',
            'company_name' =>   'ABS Wheels',
            'email'  =>  'sadiq@abswheels.se',
            'password' =>   bcrypt('123456'),
            'is_active'  =>  1,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at'  =>  date("Y-m-d H:i:s"),
        ]);

    }
}
