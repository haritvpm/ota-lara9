<?php

$factory->define(App\Overtime::class, function (Faker\Generator $faker) {
    return [
        "pen" => $faker->name,
        "designation" => $faker->name,
        "form_id" => factory('App\Form')->create(),
        "from" => $faker->name,
        "to" => $faker->name,
        "count" => $faker->randomNumber(2),
        "worknature" => $faker->name,
    ];
});
