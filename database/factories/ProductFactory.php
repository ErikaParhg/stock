<?php

use Faker\Generator as Faker;
use App\fornecedor;

$factory->define(App\Product::class, function (Faker $faker) {
    return [
    		//'supplier_id' =>factory('App\Supplier')->create(),
    		'supplier_id' => 1,
            'name' => $faker->word,
            'description' => $faker->paragraph,
            'cost' => rand(0, 1000) + (rand(0, 10) / 10),
            'quantity' => rand(10, 1000)           
        ];
});
