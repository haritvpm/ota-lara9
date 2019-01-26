<?php

$factory->define(App\Calender::class, function (Faker\Generator $faker) {
    return [
        "date" => $faker->date("d-m-Y", $max = 'now'),
        "day_type" => collect(["Sitting day","Prior day","Holiday","Intervening saturday","Intervening Working day",])->random(),
        "session_id" => factory('App\Session')->create(),
    ];
});
