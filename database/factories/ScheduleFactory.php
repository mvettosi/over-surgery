<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Schedule::class, function (Faker $faker) {
    return [
        'start_date' => '2018-01-01',
        'end_date' => '2019-01-01',
        'start_time' => '09:00:00',
        'end_time' => '18:00:00',
        'mon' => true,
        'tue' => true,
        'wed' => true,
        'thu' => true,
        'fri' => true,
        'sat' => true,
        'sun' => true,
        'user_id' => function (array $schedule) {
            return App\Models\User::where('account_type', 'doctor')->orWhere('account_type', 'nurse')->inRandomOrder()->first()->id;
        },
    ];
});