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
		$info = explode('/', $_ENV['GH_REPO']);
		$this->org = $info[0];
		$this->repo = $info[1];
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		$base_url = url();

		$repo = GitHub::repo()->show($this->org, $this->repo);
		$milestones = GitHub::issues()->milestones()->all($this->org, $this->repo);
		$hooks = Github::repo()->hooks()->all($this->org, $this->repo);

		$relevant_hooks = [];

		//Iterate through each hook
		foreach($hooks as $hook) {
			//If the hook url is set
			if(isset($hook['config']['url'])){
				//Check that it matches this base url and append to $relevant_hooks
				if(strpos($hook['config']['url'], $base_url) !== FALSE){
					array_push($relevant_hooks, $hook);
				}
			}
		}

		return ['repo' => $repo, 'milestones' => $milestones, 'hooks' => $relevant_hooks];
	}

}
