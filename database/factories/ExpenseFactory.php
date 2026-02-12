<?php

use Faker\Generator as Faker;

$factory->define(App\Expense::class, function (Faker $faker) {
    $amount = $faker->numberBetween(1000, 9000);
    $expense_type_id = $faker->numberBetween(1, 10);
    return [
        'amount' => $amount,
        'date' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'remark' => $faker->sentence($nbWords = 6, $variableNbWords = true),
        'expense_type_id' => $expense_type_id
    ];
});
