<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Transfer;
use Faker\Generator as Faker;

$factory->define(Transfer::class, function (Faker $faker) {

    $taskIdRange = \App\Task::all()->map->id->toArray();
    $userIdRange = \App\User::all()->map->id->toArray();

    $transferedTaskId = $faker->unique()->randomElement($taskIdRange);

    $task = \App\Task::find($transferedTaskId);
    $senderId = $task->user->id;

    if (($key = array_search($senderId, $userIdRange)) !== false) {
    unset($userIdRange[$key]);
    }
    $receiverId = $faker->randomElement($userIdRange);

    return [
      'transferedTaskId' => $transferedTaskId,
      'senderId' => $senderId,
      'receiverId' => $receiverId
    ];
});
