<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

// $router->get('/', function () use ($router) {
//     return $router->app->version();
// });
$router->get('/', function () use ($router) {
    $response = '<h1 style="color:#234e52; text-align: center;padding: 20% 0;">Api Raja Ongkir Lumen (7.2.1) 
    <br/> <span>By Satrio Yudho</span></h1>';
    return $response;
});

Route::get('city', 'rajaongkirController@getCity');
Route::get('province', 'rajaongkirController@getProvince');
Route::post('getCost', 'rajaongkirController@getCost');