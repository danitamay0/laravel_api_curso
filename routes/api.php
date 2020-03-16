<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/
//Buyer
Route::resource('buyers', 'Buyer\BuyerController',['only'=>['index','show']]);
Route::resource('buyers.sellers', 'Buyer\BuyerSellerController',['only'=>['index']]);
Route::resource('buyers.category', 'Buyer\BuyerCategoryController',['only'=>['index']]);
Route::resource('buyers.transactions', 'Buyer\BuyerTransactionsController',['only'=>['index']]);
Route::resource('buyers.products', 'Buyer\BuyerProductsController',['only'=>['index']]);



//Categories
Route::resource('category', 'Category\CategoryController',['except'=>['create','edit']]);
Route::resource('category.products', 'Category\CategoryProductsController',['only'=>['index']]);
Route::resource('category.seller', 'Category\CategorySellerController',['only'=>['index']]);
Route::resource('category.buyer', 'Category\CategoryBuyerController',['only'=>['index']]);
Route::resource('category.transaction', 'Category\CategoryTransactionController',['only'=>['index']]);


//Products
Route::resource('products', 'Product\ProductController',['only'=>['index','show']]);
Route::resource('products.transactions', 'Product\ProductTransactionController',['only'=>['index','show']]);
Route::resource('products.buyers', 'Product\ProductBuyerController',['only'=>['index','show']]);
Route::resource('products.category', 'Product\ProductCategoryController',['only'=>['index','destroy','update']]);

//comprar un producto hacer una transaccion
Route::resource('products.buyer.transaction', 'Product\ProductBuyerTransaction',['only'=>['store']]);


//Transaction
Route::resource('transactions', 'Transaction\TransactionController',['only'=>['index','show']]);
Route::resource('transactions.category', 'Transaction\TransactionCategoryController',['only'=>['index']]);
Route::resource('transactions.seller', 'Transaction\TransactionSellerController',['only'=>['index']]);
Route::resource('transactions.buyer', 'Transaction\TransactionBuyerController',['only'=>['index']]);

//Sellers
Route::resource('sellers', 'Seller\SellerController',['only'=>['index','show']]);
Route::resource('sellers.transaction', 'Seller\SellerTransactionController',['only'=>['index']]);
Route::resource('sellers.category', 'Seller\SellerCategoryController',['only'=>['index']]);
Route::resource('sellers.buyer', 'Seller\SellerBuyerController',['only'=>['index']]);
Route::resource('sellers.product', 'Seller\SellerProductController',['except'=>['create','edit']]);

//Users
Route::resource('users', 'User\UserController',['except'=>['create','edit']]);
Route::get('/users/verify/{token}','User\UserController@verificarUsuario')->name('verify');
Route::get('/users/{user}/resend','User\UserController@resend')->name('resend');

//Bodega
Route::Get('/bodegas','BodegaController@show');

Route::Get('/productos','ProductosController@show');
Route::post('/producto/{bodega}','ProductosController@store');
Route::post('/producto/{origen}/destino/{destino}','ProductosController@gestionar');