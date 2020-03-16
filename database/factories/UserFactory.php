<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Buyer;
use App\Category;
use App\Product;
use App\User;
use App\Seller;
use App\Transaction;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

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

$factory->define(User::class, function (Faker $faker) {

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'verified'=> $verificado=$faker->randomElement([User::VERIFICADO,User::NO_VERIFICADO]) ,
        'verification_token' => $verificado==User::VERIFICADO ? null : User::generarTokenVerificacion(),
        'admin'=> $faker ->randomElement([User::isAdmin,User::notIsAdmin]),
        'remember_token' => Str::random(10),
       
       
        
    ];
    
});

$factory->define(Category::class, function (Faker $faker) {

    return [
        'name' => $faker->word,
        'description' => $faker->paragraph(1),
        
    ];
    
});

$factory->define(Product::class, function (Faker $faker) {

    return [
        'name' => $faker->word,
        'description' => $faker->paragraph(1),
        'quantity'=> $faker->numberBetween(1,10),
        'status'=> $faker->randomElement([Product::PRODUCTO_NO_DISPONIBLE,Product::PRODUCTO_DISPONIBLE]),
        'img'=>$faker ->randomElement(['sampoo.png','makeup.png','cream.png']),
        'seller_id'=> User::all()->random()->id,
        
    ];
    
});

$factory->define(Transaction::class, function (Faker $faker) {
    $vendedor=Seller::has('products')->get()->random(); //seleccionar un Usuario que tenga algmenos un producto
    $comprador=User::all()->except($vendedor->id)->random();//busca en todos excepto el vendedor (para evitarlo) y rand saca uno
    return [
        'quantity'=> $faker->numberBetween(1,3),
        'buyer_id'=> $comprador->id,
        'product_id'=>$vendedor->products->random()->id
    ];
    
});