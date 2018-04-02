<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Medication::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->randomElement(array('Hydrocodone', 'Zocor', 'Lisinopril', 'Synthroid', 'Norvasc', 'Prilosec', 'Azithromycin', 'Amoxicillin', 'Glucophage', 'Hydrochlorothiazide')),
        'number' => $faker->sentence,
    ];
});
