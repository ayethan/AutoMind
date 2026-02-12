<?php

use Faker\Generator as Faker;

$factory->define(App\Car::class, function (Faker $faker) {
    $car_made_id = $faker->numberBetween(1, 18);
    $random_letter = strtoupper($faker->randomLetter);
    return [
        "car_no" => "{$random_letter}/{$faker->numberBetween(1000, 9999)}",
        "model" => "{$random_letter}-{$faker->numberBetween(1, 20)}",
        "car_made_id" => $car_made_id,
    ];
});
