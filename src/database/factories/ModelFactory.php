<?php

$factory->define(\BetaGT\UserAclManager\Models\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_alternativo' => $faker->unique()->safeEmail,
        'sexo'=>1,
        'imagem'=>$faker->imageUrl(250, 250),
        'password' => 'secret',
        'status' => 'ativo',
        'remember_token' => str_random(10),
    ];
});
$factory->define(\BetaGT\UserAclManager\Models\Role::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'slug' => strtolower($faker->name),
        'description' => $faker->word,
    ];
});
$factory->define(\BetaGT\UserAclManager\Models\Permission::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'slug' => [          // pass an array of permissions.
            'store'      => true,
            'view'       => true,
            'show'       => true,
            'update'     => true,
            'delete'     => true,
        ],
        'description' => $faker->word,
    ];
});