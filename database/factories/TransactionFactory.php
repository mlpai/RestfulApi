<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\product;
use App\seller;
use App\transaction;
use App\User;
use Faker\Generator as Faker;

$factory->define(transaction::class, function (Faker $faker) {
    $seller = seller::has('products')->get()->random();
    $buyer = User::all()->except($seller->id)->random();
    return [
        'quantity' => $faker->numberBetween(1,3),
        'buyer_id'=> $buyer->id,
        'product_id' => $seller->products->random()->id,
    ];
});
