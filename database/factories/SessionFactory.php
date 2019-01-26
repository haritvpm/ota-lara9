<?php

$factory->define(App\Session::class, function (Faker\Generator $faker) {
    return [
        "name" => $faker->name,
        "kla" => $faker->randomNumber(2),
        "session" => $faker->randomNumber(2),
        "dataentry_allowed" => collect(["Yes","No",])->random(),
        "show_in_datatable" => collect(["Yes","No",])->random(),
    ];
});
