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
