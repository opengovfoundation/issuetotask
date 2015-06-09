<?php namespace App\Http\Controllers;

use Rossedman\Teamwork\Facades\Teamwork;

class TeamworkController extends Controller {

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
		$this->projectId = intval($_ENV['TW_PROJECT_ID']);
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{

		$project = Teamwork::project($this->projectId)->find();
		$tasklists = Teamwork::project($this->projectId)->tasklists();
		$milestones = Teamwork::project($this->projectId)->milestones();

		return ['project' => $project['project'], 'tasklists' => $tasklists['tasklists'], 'milestones' => $milestones['milestones']];
	}

}
