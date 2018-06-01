<?php

use Faker\Generator as Faker;

$factory->define(App\Invest::class, function (Faker $faker) {
    return [
        'type' => $faker->word,
        // 'symbol' => function () {
        //     return factory(App\Stock::class)->create()->symbol;
        // },
        'stock_id' => function () {
            return factory(App\Stock::class)->create()->id;
        },
        'quant' => $faker->numberBetween($min = 100, $max = 9000),
        'price' => $faker->numberBetween($min = 1, $max = 2000),
        'broker_fee' => $faker->numberBetween($min = 1, $max = 20),
        'date_invest' => $faker->dateTime($max = 'now', $timezone = 'America/Sao_Paulo'),
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        },
        'broker_id' => function () {
            return factory(App\Broker::class)->create()->id;
        },
        //
    ];
});


// $invest->type = 'stock';
//         $invest->symbol = strtoupper($request->symbol);
//         $invest->quant = floatval($request->quant);
//         $invest->price = $request->price;
//         $invest->broker_fee = $request->broker_fee;
//         $invest->date_invest = new Carbon($request->date_invest);
//         $invest->user_id = $user->id;
//         $invest->stock_id = $stockid;
//         $invest->broker_id = $brokerid;
