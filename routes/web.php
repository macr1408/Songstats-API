<?php

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

$router->group(['prefix' => 'api/v1', 'middleware' => 'cors'], function () use ($router) {
    $router->post('auth', 'AuthController@authUser');
    $router->options('/{all}', function ($request) {
        return '';
    });
});

$router->group(['prefix' => 'api/v1', 'middleware' => ['cors', 'auth']], function () use ($router) {
    $router->get('/test', 'AuthController@test');
});


$router->get('/login', 'LoginController@login');
$router->get('/', function () use ($router) {
    return '';
});
