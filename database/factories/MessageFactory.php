<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Message;
use Faker\Generator as Faker;

$factory->define(Message::class, function (Faker $faker) {
    $arrayOfUsersIds=[1,3,4];
    do{
        $from = $arrayOfUsersIds[array_rand($arrayOfUsersIds)];
        $to = $arrayOfUsersIds[array_rand($arrayOfUsersIds)];
    }while($from === $to);
    return [
        'from' => $from,
        'to' => $to,
        'text' => $faker->sentence,
    ];
});
