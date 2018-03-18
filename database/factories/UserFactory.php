<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
 */

$factory->define(App\Models\User::class, function (Faker $faker) {
    $name = $faker->firstName;
    $surname = $faker->lastName;
    $username = strtolower($name . "." . $surname);
    return [
        'username' => $username,
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'name' => $name,
        'surname' => $surname,
        'email' => $faker->unique()->safeEmail,
        'phone' => $faker->phoneNumber,
        'address' => $faker->address,
        'postcode' => $faker->postcode,
        'document_type' => $faker->randomElement(array('identity_card', 'passport')),
        'document_id' => str_random(10),
        'birthdate' => $faker->date,
        'account_type' => $faker->unique()->randomElement(array('receptionist', 'patient', 'admin', 'doctor', 'nurse')),
        'remember_token' => str_random(10),
    ];
});
