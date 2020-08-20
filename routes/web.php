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

// API route group
$router->group(['prefix' => 'api'], function () use ($router) {
    // Matches "/api/register
    $router->post('register', 'AuthController@register');
    $router->post('login', 'AuthController@login');
    $router->get('users', 'UserController@profile');

    $router->get('city', 'rajaongkirController@getCity');
    $router->get('province', 'rajaongkirController@getProvince');
    $router->get('getLocations', 'rajaongkirController@getLocations');
    $router->post('getCost', 'rajaongkirController@getCost');
});

