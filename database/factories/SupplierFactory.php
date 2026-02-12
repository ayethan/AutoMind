<?php

use App\Supplier;
use Faker\Generator as Faker;

$factory->define(Supplier::class, function (Faker $faker) {
    return [
        "name" => $faker->name,
        "company_name" => $faker->name,
        "phone" => $faker->tollFreePhoneNumber,
        "remark" => $faker->sentence($nbWords = 10, $variableNbWords = true)
    ];
});
