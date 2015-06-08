<?php namespace App\Http\Controllers;

use GrahamCampbell\GitHub\Facades\GitHub;

class GithubController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//$this->github = $github;
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{

		$issues = GitHub::repo()->hooks();
		return ['hooks' => $issues];

		return ['issues' => $issues];
	}

}
