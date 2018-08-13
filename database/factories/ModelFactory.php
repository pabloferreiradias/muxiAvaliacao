<?php
use Illuminate\Support\Facades\Hash;

$factory->define(
    App\Models\User::class,
    function (Faker\Generator $faker) {
        return [
            'name' => $faker->name,
            'login' => $faker->username,
            'password' => Hash::make('secret'),
        ];
    }
);

$factory->define(
    App\Models\Order::class,
    function (Faker\Generator $faker) {
        return [
            'pos_code' => str_random(10),
            'value' => mt_rand(10, 100)
        ];
    }
);

$factory->define(
    App\Models\OrderServer::class,
    function (Faker\Generator $faker) {
        return [
            'pos_code' => str_random(10),
            'value' => mt_rand(10, 100)
        ];
    }
);
