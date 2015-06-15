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

  public function getGithubMilestoneStatus() {
    $syncs = [];

    try{
      $GH_milestones = GitHub::issues()->milestones()->all($this->org, $this->repo);
      $TW_milestones = Teamwork::project($this->projectId)->milestones();

      foreach($GH_milestones as $GH_milestone) {
        $found = false;
        $title = $GH_milestone['title'];

        //Check milestone existence by title
        foreach($TW_milestones['milestones'] as $TW_milestone) {
          if($TW_milestone['title'] === $title){
            $found = true;
          }
        }

        array_push($syncs, ['title' => $title, 'milestone_exists' => $found, 'synced' => $found]);
      }

      //$milestone = Github::issues()->milestones()->show($this->org, $this->repo, $id);
    } catch (\RuntimeException $e) {
      //Milestone not found
      return (new Response(['message' => "Error: " . $e->getMessage()], 500));
    }

    //$date = date('Ymd', strtotime($milestone['due_on']));

    return ['syncs' => $syncs];
  }

  public function createTWMilestone($GH_milestone) {
    return Teamwork::project($this->projectId)->createMilestone([
        'title'                 => $GH_milestone['title'],
        'description'           => $GH_milestone['description'],
        'deadline'              => date('Ymd', strtotime($GH_milestone['due_on'])),
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

  // public function createTWTasklist($GH_milestone, $TW_milestone) {
  //   $tasklist = Teamwork::project($this->projectId)->createTasklist({
  //     'name'          => '',
  //     'private'       => '',
  //     'pinned'        => '',
  //     'milestone-id'  => '',
  //     'description'   => '',
  //     ''
  //   });
  // }
}
