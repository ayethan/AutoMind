<?php

use Faker\Generator as Faker;

$factory->define(App\ExpenseType::class, function (Faker $faker) {
    return [
        'name' => $faker->word(),
        'remark' => $faker->text($maxNbChars = 200)
    ];
});
