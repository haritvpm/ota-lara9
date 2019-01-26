<?php

$factory->define(App\Setting::class, function (Faker\Generator $faker) {
    return [
        "name" => $faker->name,
        "value" => $faker->name,
    ];
});
