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


$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/callback', 'SocialAuthFacebookController@callback');

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->get('/login', 'SocialAuthFacebookController@redirect');
    $router->get('teachers',  ['uses' => 'TeachersController@showAllTeachers']);
    $router->get('options',  ['uses' => 'OptionsController@get']);
});


$router->group(['middleware' => ['jwt'], 'prefix' => 'api'], function () use ($router) {
    $router->get('me',  ['uses' => 'UserController@get']);
    $router->get('teachers/{teacherId}', ['uses' => 'TeachersController@get']);
    $router->post('teachers', ['uses' => 'TeachersController@post']);
    $router->put('teacher', ['uses' => 'TeachersController@update']);
    $router->delete('teachers/{id}', ['uses' => 'TeachersController@delete']);
    $router->put('teachers/{id}', ['uses' => 'TeachersController@update']);
    $router->get('/logout', 'SocialAuthFacebookController@logout');
});
