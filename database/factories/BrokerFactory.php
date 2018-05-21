<?php

use Faker\Generator as Faker;

$factory->define(App\Broker::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence,
        'cnpj' => $faker->randomNumber($nbDigits = null, $strict = false),
    ];
});
