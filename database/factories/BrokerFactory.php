<?php

use Faker\Generator as Faker;

$factory->define(App\Broker::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence,
        'cnpj' => $faker->numberBetween($min = 000000000000000001, $max = 999999999999999999),
    ];
});
