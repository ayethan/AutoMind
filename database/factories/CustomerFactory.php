<?php

use Faker\Generator as Faker;

$factory->define(App\Customer::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'phone' => $faker->phoneNumber ,
        'remark' => $faker->sentence($nbWords = 6, $variableNbWords = true),
    ];
});
