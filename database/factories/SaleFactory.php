<?php

use Faker\Generator as Faker;

$factory->define(App\Sale::class, function (Faker $faker) {
    $rand_letter = strtoupper($faker->randomLetter);
    return [
        "car_id" => $faker->numberBetween(1, 200),
        "date" => $faker->date($format = 'Y-m-d', $max = 'now'),
        "is_paid" => $faker->boolean,
        "status" => $faker->numberBetween($min=1, $max=3)
    ];
});
