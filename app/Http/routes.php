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
  if ($_ENV['APP_DEBUG']) {
      return File::get(public_path() . '/pre-build.html');
  } else {
      return File::get(public_path() . '/index.html');
  }
})->where('slug', '^(?!api)(.*)$');

Route::get('api', 'AppController@index');

Route::get('api/github', 'GithubController@index');

Route::get('api/teamwork', 'TeamworkController@index');

Route::post('api/sync/github/milestone', 'SyncController@postSyncGithubMilestone');

// Route::controllers([
// 	'auth' => 'Auth\AuthController',
// 	'password' => 'Auth\PasswordController',
// ]);
