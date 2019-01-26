<?php

$factory->define(App\Routing::class, function (Faker\Generator $faker) {
    return [
        "user_id" => factory('App\User')->create(),
        "route" => $faker->name,
    ];
});
