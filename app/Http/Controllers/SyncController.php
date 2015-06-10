<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Log;

use GrahamCampbell\GitHub\Facades\GitHub;
use Rossedman\Teamwork\Facades\Teamwork;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SyncController extends Controller {
  public function __construct() {
    $info = explode('/', $_ENV['GH_REPO']);
    $this->org = $info[0];
    $this->repo = $info[1];

    $this->projectId = intval($_ENV['TW_PROJECT_ID']);
    $this->personId = intval($_ENV['TW_PERSON_ID']);
  }

  public function postSyncGithubMilestone(Request $request) {
    $number = $request->input('number');

    try {
      $GH_milestone = Github::issues()->milestones()->show($this->org, $this->repo, $number);  
      
      $TW_milestones = Teamwork::project($this->projectId)->milestones();

      $TW_milestone = $this->milestoneMatch($GH_milestone, $TW_milestones);

      Log::info($TW_milestone);

      if(!$TW_milestone) {

        return $this->createTWMilestone($GH_milestone);
      }
    } catch (\RuntimeException $e) {
      Log::error($e);
      return (new Response(['message', $e->getMessage()], 500));
    } catch (\Exception $e) {
      Log::error($e);
      return (new Response(['message', $e->getMessage()], 500));
    }

    return ['milestone' => $TW_milestone, 'message' => 'Teamwork milestone already exists'];
  }

  public function getGithubMilestone() {
    $id = 7;

    try{
      $milestone = Github::issues()->milestones()->show($this->org, $this->repo, $id);
    } catch (\RuntimeException $e) {
      //Milestone not found
      return (new Response(['message' => "Error: " . $e->getMessage()], 500));
    }
      
    return $milestone;
  }

  public function createTWMilestone($GH_milestone) {
    return Teamwork::project($this->projectId)->createMilestone([
        'title'                 => $GH_milestone['title'],
        'description'           => $GH_milestone['description'],
        'deadline'              => '20150402',
        'notify'                => false,
        'reminder'              => false,
        'responsible-party-ids' => $this->personId
      ]);
  }

  public function milestoneMatch($GH_milestone, $TW_milestones){
    $title = $GH_milestone['title'];

    foreach($TW_milestones['milestones'] as $milestone) {
      if($milestone['title'] === $title) {
        return $milestone;
      }
    }

    return false;
  }
}
