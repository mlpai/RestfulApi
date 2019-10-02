<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\product;
use App\user;
use Faker\Generator as Faker;

$factory->define(product::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->paragraph(1),
        'quantity' => $faker->numberBetween(1,10),
        'status' => $faker->randomElement([product::AVAILABLE_PRODUCT,product::UNAVAILABLE_PRODUCT]),
        'image' => $faker->numberBetween(1,5),
        'seller_id' => user::all()->random()->id,
    ];
});
