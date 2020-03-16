<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Transaction;
use App\Category;
use App\Product;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
    
        DB::statement('SET foreign_key_checks = 0'); //desactiva las claves foraneas
       //elmina la informacion 
       User::truncate();
       Category::truncate();
       Product::truncate();
       Transaction::truncate();
       DB::table('category_product')->truncate();

       $cantidadUSer=200;
       $cantidadCategories=30;
       $cantidadProducts=1000;
       $cantidadTransaction=100;
        
       #esto hace que los eventos no sean usados en los seed
       User::flushEventListeners();
        Transaction::flushEventListeners();
        Category::flushEventListeners();
        Product::flushEventListeners();


       factory(User::class,$cantidadUSer)->create();
        factory(Category::class,$cantidadCategories)->create();

        factory(Product::class,$cantidadProducts)->create()->each(
            function($producto){
                $categoria=Category::all()->random(mt_rand(1,5))->pluck('id');
                $producto->categories()->attach($categoria);
            }
        );

        factory(Transaction::class,$cantidadTransaction)->create();
    }
}
