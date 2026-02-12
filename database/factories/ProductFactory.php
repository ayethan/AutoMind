<?php

use Faker\Generator as Faker;

$factory->define(App\Product::class, function (Faker $faker) {

    $buy_price = $faker->numberBetween(1000, 9000);

    return [
        'name' => $faker->word(),
        'code' => $faker->randomLetter.$faker->unique()->numberBetween(10000, 99999),
        'stock' => $faker->numberBetween(5, 30),
        'buy_price' => $buy_price,
        'sell_price' => $buy_price + $faker->numberBetween(1000, 3000),
        'remark' => $faker->text($maxNbChars = 200)
    ];
});
