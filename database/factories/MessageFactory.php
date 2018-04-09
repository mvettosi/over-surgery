<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Message::class, function (Faker $faker) {
    return [
        'message' => $faker->text(99),
        'user_id' => function (array $post) {
            return App\Models\User::where('account_type', 'patient')->first()->id;
        },
    ];
});
