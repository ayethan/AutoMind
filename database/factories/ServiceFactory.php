<?php

use Faker\Generator as Faker;

$factory->define(App\Service::class, function (Faker $faker) {

    return [
        'name' => $faker->word(),
        'description' => $faker->text($maxNbChars = 200),
        'price' => $faker->numberBetween(1000, 9000),
        'remark' => $faker->text($maxNbChars = 200)
    ];
});
