<?php

use Faker\Generator as Faker;
use Faker\Provider\pt_BR\Company;

$factory->define(App\Supplier::class, function (Faker $faker) {
    return [
            'name' => $faker->company,
            'cnpj' => 98348848283446,
            'address' => $faker->address,
        ];
});
