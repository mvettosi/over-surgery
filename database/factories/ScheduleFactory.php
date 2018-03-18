<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Schedule::class, function (Faker $faker) {
    return [
        'start_time' => $faker->time,
        'end_time' => $faker->time,
        'start_date' => $faker->date,
        'end_date' => $faker->date,
        'day' => $faker->randomElement(array('mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun')),
        'worker_id' => function (array $post) {
            return App\Models\User::where('account_type', 'doctor')->orWhere('account_type', 'nurse')->inRandomOrder()->first()->id;
        },
    ];
});