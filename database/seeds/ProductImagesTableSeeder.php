<?php

use Illuminate\Database\Seeder;

class ProductImagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('product_images')->delete();
  
        DB::table('product_images')->insert([
            'product_id'    =>  1,
            'name' =>   'TPMS_1.jpg',
            'path' =>   'images/product/accessories/TPMS_1.jpg',
            'thumbnail_path'    =>  'images/product/accessories/TPMS_1.jpg',
            'priority' =>   1,
        ]);

        DB::table('product_images')->insert([
            'product_id'     =>  2,
            'name' =>    'monteringsKit.jpg',
            'path' =>  'images/product/accessories/monteringsKit.jpg',
            'thumbnail_path'    =>  'images/product/accessories/monteringsKit.jpg',
            'priority' =>  1,
        ]);

        // DB::table('product_images')->insert([
        //     'product_id'     =>  3,
        //     'name' =>    'balansering.png',
        //     'path' =>  'images/product/accessories/balansering.png',
        //     'thumbnail_path'    =>  'images/product/accessories/balansering.png',
        //     'priority' =>  1,
        // ]);

        // DB::table('product_images')->insert([
        //     'product_id'     =>  4,
        //     'name' =>    'car_change.jpg',
        //     'path' =>  'images/product/accessories/wrench.png',
        //     'thumbnail_path'    =>  'images/product/accessories/wrench.png',
        //     'priority' =>  1,
        // ]);

        DB::table('product_images')->insert([
            'product_id'     =>  3,
            'name' =>    'lock-kit.jpg',
            'path' =>  'images/product/accessories/lock-kit.jpg',
            'thumbnail_path'    =>  'images/product/accessories/lock-kit.jpg',
            'priority' =>  1,
        ]);


        // DB::table('product_images')->insert([
        //     'product_id'     =>  6,
        //     'name' =>    'bolts.jpg',
        //     'path' =>  'images/product/accessories/bolts.jpg',
        //     'thumbnail_path'    =>  'images/product/accessories/bolts.jpg',
        //     'priority' =>  1,
        // ]);

        // DB::table('product_images')->insert([
        //     'product_id'     =>  7,
        //     'name' =>    'bolts.jpg',
        //     'path' =>  'images/product/accessories/bolts.jpg',
        //     'thumbnail_path'    =>  'images/product/accessories/bolts.jpg',
        //     'priority' =>  1,
        // ]);

        // DB::table('product_images')->insert([
        //     'product_id'     =>  8,
        //     'name' =>    'bolts.jpg',
        //     'path' =>  'images/product/accessories/bolts.jpg',
        //     'thumbnail_path'    =>  'images/product/accessories/bolts.jpg',
        //     'priority' =>  1,
        // ]);

        // DB::table('product_images')->insert([
        //     'product_id'     =>  9,
        //     'name' =>    'bolts.jpg',
        //     'path' =>  'images/product/accessories/bolts.jpg',
        //     'thumbnail_path'    =>  'images/product/accessories/bolts.jpg',
        //     'priority' =>  1,
        // ]);

        // DB::table('product_images')->insert([
        //     'product_id'     =>  10,
        //     'name' =>    'bolts.jpg',
        //     'path' =>  'images/product/accessories/bolts.jpg',
        //     'thumbnail_path'    =>  'images/product/accessories/bolts.jpg',
        //     'priority' =>  1,
        // ]);

        // DB::table('product_images')->insert([
        //     'product_id'     =>  11,
        //     'name' =>    'nuts.jpg',
        //     'path' =>  'images/product/accessories/nuts.jpg',
        //     'thumbnail_path'    =>  'images/product/accessories/nuts.jpg',
        //     'priority' =>  1,
        // ]);

        // DB::table('product_images')->insert([
        //     'product_id'     =>  12,
        //     'name' =>    'nuts.jpg',
        //     'path' =>  'images/product/accessories/nuts.jpg',
        //     'thumbnail_path'    =>  'images/product/accessories/nuts.jpg',
        //     'priority' =>  1,
        // ]);

        // DB::table('product_images')->insert([
        //     'product_id'     =>  13,
        //     'name' =>    'nuts.jpg',
        //     'path' =>  'images/product/accessories/nuts.jpg',
        //     'thumbnail_path'    =>  'images/product/accessories/nuts.jpg',
        //     'priority' =>  1,
        // ]);

        // DB::table('product_images')->insert([
        //     'product_id'     =>  14,
        //     'name' =>    'nuts.jpg',
        //     'path' =>  'images/product/accessories/nuts.jpg',
        //     'thumbnail_path'    =>  'images/product/accessories/nuts.jpg',
        //     'priority' =>  1,
        // ]);

        // DB::table('product_images')->insert([
        //     'product_id'     =>  15,
        //     'name' =>    'rings.jpg',
        //     'path' =>  'images/product/accessories/rings.jpg',
        //     'thumbnail_path'    =>  'images/product/accessories/rings.jpg',
        //     'priority' =>  1,
        // ]);

        // DB::table('product_images')->insert([
        //     'product_id'     =>  16,
        //     'name' =>    'rings.jpg',
        //     'path' =>  'images/product/accessories/rings.jpg',
        //     'thumbnail_path'    =>  'images/product/accessories/rings.jpg',
        //     'priority' =>  1,
        // ]);

        // DB::table('product_images')->insert([
        //     'product_id'     =>  17,
        //     'name' =>    'rings.jpg',
        //     'path' =>  'images/product/accessories/rings.jpg',
        //     'thumbnail_path'    =>  'images/product/accessories/rings.jpg',
        //     'priority' =>  1,
        // ]);

        // DB::table('product_images')->insert([
        //     'product_id'     =>  18,
        //     'name' =>    'rings.jpg',
        //     'path' =>  'images/product/accessories/rings.jpg',
        //     'thumbnail_path'    =>  'images/product/accessories/rings.jpg',
        //     'priority' =>  1,
        // ]);

        // DB::table('product_images')->insert([
        //     'product_id'     =>  19,
        //     'name' =>    'rings.jpg',
        //     'path' =>  'images/product/accessories/rings.jpg',
        //     'thumbnail_path'    =>  'images/product/accessories/rings.jpg',
        //     'priority' =>  1,
        // ]);

        // DB::table('product_images')->insert([
        //     'product_id'     =>  20,
        //     'name' =>    'rings.jpg',
        //     'path' =>  'images/product/accessories/rings.jpg',
        //     'thumbnail_path'    =>  'images/product/accessories/rings.jpg',
        //     'priority' =>  1,
        // ]);

        // DB::table('product_images')->insert([
        //     'product_id'     =>  21,
        //     'name' =>    'rings.jpg',
        //     'path' =>  'images/product/accessories/rings.jpg',
        //     'thumbnail_path'    =>  'images/product/accessories/rings.jpg',
        //     'priority' =>  1,
        // ]);

        // DB::table('product_images')->insert([
        //     'product_id'     =>  22,
        //     'name' =>    'rings.jpg',
        //     'path' =>  'images/product/accessories/rings.jpg',
        //     'thumbnail_path'    =>  'images/product/accessories/rings.jpg',
        //     'priority' =>  1,
        // ]);

        // DB::table('product_images')->insert([
        //     'product_id'     =>  23,
        //     'name' =>    'rings.jpg',
        //     'path' =>  'images/product/accessories/rings.jpg',
        //     'thumbnail_path'    =>  'images/product/accessories/rings.jpg',
        //     'priority' =>  1,
        // ]);

        // DB::table('product_images')->insert([
        //     'product_id'     =>  24,
        //     'name' =>    'rings.jpg',
        //     'path' =>  'images/product/accessories/rings.jpg',
        //     'thumbnail_path'    =>  'images/product/accessories/rings.jpg',
        //     'priority' =>  1,
        // ]);

        // DB::table('product_images')->insert([
        //     'product_id'     =>  25,
        //     'name' =>    'rings.jpg',
        //     'path' =>  'images/product/accessories/rings.jpg',
        //     'thumbnail_path'    =>  'images/product/accessories/rings.jpg',
        //     'priority' =>  1,
        // ]);

        // DB::table('product_images')->insert([
        //     'product_id'     =>  26,
        //     'name' =>    'rings.jpg',
        //     'path' =>  'images/product/accessories/rings.jpg',
        //     'thumbnail_path'    =>  'images/product/accessories/rings.jpg',
        //     'priority' =>  1,
        // ]);

        // DB::table('product_images')->insert([
        //     'product_id'     =>  27,
        //     'name' =>    'rings.jpg',
        //     'path' =>  'images/product/accessories/rings.jpg',
        //     'thumbnail_path'    =>  'images/product/accessories/rings.jpg',
        //     'priority' =>  1,
        // ]);

        // DB::table('product_images')->insert([
        //     'product_id'     =>  28,
        //     'name' =>    'rings.jpg',
        //     'path' =>  'images/product/accessories/rings.jpg',
        //     'thumbnail_path'    =>  'images/product/accessories/rings.jpg',
        //     'priority' =>  1,
        // ]);

        // DB::table('product_images')->insert([
        //     'product_id'     =>  29,
        //     'name' =>    'rings.jpg',
        //     'path' =>  'images/product/accessories/rings.jpg',
        //     'thumbnail_path'    =>  'images/product/accessories/rings.jpg',
        //     'priority' =>  1,
        // ]);

        // DB::table('product_images')->insert([
        //     'product_id'     =>  30,
        //     'name' =>    'rings.jpg',
        //     'path' =>  'images/product/accessories/rings.jpg',
        //     'thumbnail_path'    =>  'images/product/accessories/rings.jpg',
        //     'priority' =>  1,
        // ]);

        // DB::table('product_images')->insert([
        //     'product_id'     =>  31,
        //     'name' =>    'rings.jpg',
        //     'path' =>  'images/product/accessories/rings.jpg',
        //     'thumbnail_path'    =>  'images/product/accessories/rings.jpg',
        //     'priority' =>  1,
        // ]);

        // DB::table('product_images')->insert([
        //     'product_id'     =>  32,
        //     'name' =>    'rings.jpg',
        //     'path' =>  'images/product/accessories/rings.jpg',
        //     'thumbnail_path'    =>  'images/product/accessories/rings.jpg',
        //     'priority' =>  1,
        // ]);

        // DB::table('product_images')->insert([
        //     'product_id'     =>  33,
        //     'name' =>    'rings.jpg',
        //     'path' =>  'images/product/accessories/rings.jpg',
        //     'thumbnail_path'    =>  'images/product/accessories/rings.jpg',
        //     'priority' =>  1,
        // ]);

        // DB::table('product_images')->insert([
        //     'product_id'     =>  34,
        //     'name' =>    'rings.jpg',
        //     'path' =>  'images/product/accessories/rings.jpg',
        //     'thumbnail_path'    =>  'images/product/accessories/rings.jpg',
        //     'priority' =>  1,
        // ]);

        // DB::table('product_images')->insert([
        //     'product_id'     =>  35,
        //     'name' =>    'rings.jpg',
        //     'path' =>  'images/product/accessories/rings.jpg',
        //     'thumbnail_path'    =>  'images/product/accessories/rings.jpg',
        //     'priority' =>  1,
        // ]);

        // DB::table('product_images')->insert([
        //     'product_id'     =>  36,
        //     'name' =>    'rings.jpg',
        //     'path' =>  'images/product/accessories/rings.jpg',
        //     'thumbnail_path'    =>  'images/product/accessories/rings.jpg',
        //     'priority' =>  1,
        // ]);

        // DB::table('product_images')->insert([
        //     'product_id'     =>  37,
        //     'name' =>    'rings.jpg',
        //     'path' =>  'images/product/accessories/rings.jpg',
        //     'thumbnail_path'    =>  'images/product/accessories/rings.jpg',
        //     'priority' =>  1,
        // ]);

        // DB::table('product_images')->insert([
        //     'product_id'     =>  38,
        //     'name' =>    'rings.jpg',
        //     'path' =>  'images/product/accessories/rings.jpg',
        //     'thumbnail_path'    =>  'images/product/accessories/rings.jpg',
        //     'priority' =>  1,
        // ]);

        // DB::table('product_images')->insert([
        //     'product_id'     =>  39,
        //     'name' =>    'rings.jpg',
        //     'path' =>  'images/product/accessories/rings.jpg',
        //     'thumbnail_path'    =>  'images/product/accessories/rings.jpg',
        //     'priority' =>  1,
        // ]);

        // DB::table('product_images')->insert([
        //     'product_id'     =>  40,
        //     'name' =>    'rings.jpg',
        //     'path' =>  'images/product/accessories/rings.jpg',
        //     'thumbnail_path'    =>  'images/product/accessories/rings.jpg',
        //     'priority' =>  1,
        // ]);



        // DB::table('product_images')->insert([
        //     'product_id'     =>  41,
        //     'name' =>    'spacer.jpg',
        //     'path' =>  'images/product/accessories/spacer.jpg',
        //     'thumbnail_path'    =>  'images/product/accessories/spacer.jpg',
        //     'priority' =>  1,
        // ]);

        // DB::table('product_images')->insert([
        //     'product_id'     =>  42,
        //     'name' =>    'spacer.jpg',
        //     'path' =>  'images/product/accessories/spacer.jpg',
        //     'thumbnail_path'    =>  'images/product/accessories/spacer.jpg',
        //     'priority' =>  1,
        // ]);

        // DB::table('product_images')->insert([
        //     'product_id'     =>  43,
        //     'name' =>    'spacer.jpg',
        //     'path' =>  'images/product/accessories/spacer.jpg',
        //     'thumbnail_path'    =>  'images/product/accessories/spacer.jpg',
        //     'priority' =>  1,
        // ]);

        // DB::table('product_images')->insert([
        //     'product_id'     =>  44,
        //     'name' =>    'spacer.jpg',
        //     'path' =>  'images/product/accessories/spacer.jpg',
        //     'thumbnail_path'    =>  'images/product/accessories/spacer.jpg',
        //     'priority' =>  1,
        // ]);

        // DB::table('product_images')->insert([
        //     'product_id'     =>  45,
        //     'name' =>    'spacer.jpg',
        //     'path' =>  'images/product/accessories/spacer.jpg',
        //     'thumbnail_path'    =>  'images/product/accessories/spacer.jpg',
        //     'priority' =>  1,
        // ]);

        // DB::table('product_images')->insert([
        //     'product_id'     =>  46,
        //     'name' =>    'spacer.jpg',
        //     'path' =>  'images/product/accessories/spacer.jpg',
        //     'thumbnail_path'    =>  'images/product/accessories/spacer.jpg',
        //     'priority' =>  1,
        // ]);
    }
}
