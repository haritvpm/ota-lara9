<?php

$factory->define(App\Employee::class, function (Faker\Generator $faker) {
    return [
        "name" => $faker->name,
        "pen" => $faker->name,
        "designation_id" => factory('App\Designation')->create(),
    ];
});
