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
//     return $router->app->version();
    return view('index');
});

$router->group(['prefix' => 'api', 'middleware' => 'auth'], function () use ($router) {
	$router->get('tasks', ['uses' => 'TaskController@showAllTasks']);
	
	$router->get('tasks/{id}', ['uses' => 'TaskController@showOneTask']);
	
	$router->post('tasks', ['uses' => 'TaskController@create']);
	
	$router->delete('tasks/{id}', ['uses' => 'TaskController@delete']);
	
	$router->put('tasks/{id}', ['uses' => 'TaskController@update']);
	
	$router->get('user/log', ['uses' => 'UserController@showLog']);
});

$router->group(['prefix' => 'api'], function () use ($router) {
	$router->post('user/login', ['uses' => 'UserController@login']);
	
	$router->post('user/create', ['uses' => 'UserController@create']);
	
});

$router->get('user/request-change-password', function () {
	return view('RequestPasswordChange');
});

$router->post('user/request-change-password', ['uses' => 'UserController@requestPasswordChange']);
$router->get('user/password-change-form/{hash}', ['uses' => 'UserController@passwordChangeForm']);
$router->post('user/password-change', ['uses' => 'UserController@passwordChange']);





