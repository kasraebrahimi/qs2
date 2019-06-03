<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Task;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(App\Task::class, function ($faker) {
    return [
        'name' => $faker->word,
        'status' => 0
    ];
});
