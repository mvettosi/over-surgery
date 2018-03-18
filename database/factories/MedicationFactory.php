<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Medication::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence,
        'number' => $faker->sentence,
    ];
});
