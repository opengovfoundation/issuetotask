<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::any('{slug}', function ($slug) {
  if (!Config::get('app.debug')) {
      return File::get(public_path() . '/index.html');
  } else {
      return File::get(public_path() . '/build/index.html');
  }
})->where('slug', '^(?!api/)(.*)$');

Route::get('/', 'WelcomeController@index');

Route::get('home', 'HomeController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
