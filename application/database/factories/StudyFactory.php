<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Study;
use Faker\Generator as Faker;

$array['name'] = array('Fizzy Drinks', 'Primary Schools (es1)', 'Primary School Pupil Ethnicity (es1)', 'Primary School Teacher Details');


$factory->define(Study::class, function (Faker $faker) {
    return [
       'name' => '',
        'active' => false,
        'description' => $faker->paragraph(3),
        'start_date' => $faker->date('Y-m-d'),
        'end_date' => $faker->dateTimeInInterval('+100 days'),
        'portal_id' => 1,
        'num_of_pages' => 1,
        'created_by' => 1
    ];
});


$factory->state(App\Study::class, 'fizzyDrinks', [
    'name' => $array['name'][0],
    'active' => 1,
]);
$factory->state(App\Study::class, 'primarySchools', [
    'name' => $array['name'][1],
    'active' => false,
]);
$factory->state(App\Study::class, 'primarySchoolPupilEthnicity', [
    'name' => $array['name'][2],
    'active' => 1,
]);
$factory->state(App\Study::class, 'primarySchoolTeacherDetails', [
    'name' => $array['name'][3],
    'active' => 1,
]);
