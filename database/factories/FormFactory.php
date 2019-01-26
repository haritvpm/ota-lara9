<?php

$factory->define(App\Form::class, function (Faker\Generator $faker) {
    return [
        "session" => $faker->name,
        "creator" => $faker->name,
        "owner" => $faker->name,
        "form_no" => $faker->randomNumber(2),
        "overtime_slot" => collect(["First","Second","Third","Sittings",])->random(),
        "duty_date" => $faker->date("d-m-Y", $max = 'now'),
        "date_from" => $faker->date("d-m-Y", $max = 'now'),
        "date_to" => $faker->date("d-m-Y", $max = 'now'),
    ];
});
