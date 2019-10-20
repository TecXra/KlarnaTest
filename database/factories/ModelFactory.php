<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});


$factory->define(App\Product::class, function (Faker\Generator $faker) {
    return [
        'CategoryID' => '1',
        'MainSupplierID' => '1',
        'Price' => $faker->numberBetween(1000, 6000),
        'Quantity' => $faker->randomNumber(2),
    ];
});

$factory->define(App\Supplier::class, function (Faker\Generator $faker) {
    return [
        'CompanyName' => 'GMG'
    ];
});

$factory->define(App\ProductImage::class, function (Faker\Generator $faker) {
    return [
        'ProductID' => 1,
        'Path' => 'images/product/KF550.jpg',
        'Priority' => 1,
    ];
});

