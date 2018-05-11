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

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Company::class, function (Faker $faker) {
	return [
		'name' => $faker->company,
		'website' => $faker->url,
		'shipping_address_1' => $faker->streetAddress,
		'shipping_city' => $faker->city,
		'shipping_state' => $faker->state,
		'shipping_zip' => $faker->postcode,
		'billing_address_1' => $faker->streetAddress,
		'billing_address_2' => $faker->secondaryAddress, 
		'billing_city' => $faker->city, 
		'billing_state' => $faker->state, 
		'billing_zip' => $faker->postcode
	];
});

$factory->define(App\Contact::class, function (Faker $faker) {
	return [
		'first_name' => $faker->firstName,
		'last_name' => $faker->lastName,
		'email' => $faker->safeEmail,
		'phone' => $faker->phoneNumber,
		'title' => $faker->jobTitle,
	];
});

$factory->define(App\Task::class, function (Faker $faker) {
	return [
		'title' => $faker->sentence,
		'description' => $faker->paragraph,
		'type' => 'Task',
		'status' => 'New'
	];
});

$factory->define(App\Address::class, function (Faker $faker) {
	return [
		'address_1' => $faker->streetAddress,
		'address_2' => $faker->secondaryAddress,
		'city' => $faker->city,
		'state'=> $faker->state,
		'zip' => $faker->postcode, 
		'role' => 'shipping',
		'country' => 'United States'
	];
});

$factory->define(App\Quote::class, function (Faker $faker) {
	return [
		'title' => 'Quote for ' . $faker->company,
		'good_until' => Carbon\Carbon::now(),
		'status' => 'New',
		'shipping_method' => 'Best Ground', 
		'shipping_cost' => 10,
		'value' => 10
	];
});

$factory->define(App\LineItem::class, function (Faker $faker) {
	return [
		'part_id' => 2,
		'quantity' => 4,
		'sale_price' => $faker->randomNumber(2),
		'lineable_type' => 'Quote',
	];
});
