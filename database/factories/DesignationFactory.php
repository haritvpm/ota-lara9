<?php

$factory->define(App\Designation::class, function (Faker\Generator $faker) {
    return [
        "designation" => $faker->name,
        "rate" => $faker->randomNumber(2),
    ];
});
