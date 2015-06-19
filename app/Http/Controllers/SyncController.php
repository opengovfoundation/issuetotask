<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\TeamworkHelper;

use Log;

use GrahamCampbell\GitHub\Facades\GitHub;
use Rossedman\Teamwork\Facades\Teamwork;
use Github\ResultPager;
use Github\Client;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SyncController extends Controller {
  public function __construct() {
    $info = explode('/', $_ENV['GH_REPO']);
    $this->org = $info[0];
    $this->repo = $info[1];
    $this->GH_token = $_ENV['GH_TOKEN'];

    $this->projectId = intval($_ENV['TW_PROJECT_ID']);
    $this->personId = intval($_ENV['TW_PERSON_ID']);
  }

  public function postGithubWebhook(Request $request) {
    $action = $request->input('action');
    $issue = $request->input('issue');
    $response = ['message' => ''];

    $helper = new TeamworkHelper();

    switch($action) {
      case 'opened'://Issue created
        //Grab tasklist based on milestone
        //$tasklist = $helper->findTaskListByName();
        break;
      case 'closed'://Issue closed
        $task = $helper->findTaskByName($issue['title']);
        return $helper->closeTask($task);
        break;
      case 'reopened'://Issue re-opened
        break;
      case 'created'://Issue commented
        break;
      default:
    }

    return $response;
  }

  public function postSyncGithubMilestone(Request $request) {
    $number = $request->input('number');

    try {
      $GH_milestone = Github::issues()->milestones()->show($this->org, $this->repo, $number);  
      $TW_milestones = Teamwork::project($this->projectId)->milestones();

      $TW_milestone = $this->milestoneMatch($GH_milestone, $TW_milestones);

      if(!$TW_milestone) {
        $milestone = $this->createTWMilestone($GH_milestone);

        $milestoneId = $milestone['milestoneId'];

        $tasklist = $this->createTWTasklist($milestoneId, $GH_milestone);

        $this->createTasks($GH_milestone['number'], $tasklist['TASKLISTID']);
        
        return ['milestone' => $milestone, 'tasklist' => $tasklist];
      } else {
        $TW_milestone_id = $TW_milestone['id'];

        $tasklists = Teamwork::project(intval($this->projectId))->tasklists();

        $found = false;

        //Find attached tasklist and delete it
        foreach($tasklists['tasklists'] as $tasklist) {
          if(intval($tasklist['milestone-id']) === intval($TW_milestone_id)) {
            Teamwork::tasklist(intval($tasklist['id']))->delete();
          }
        }

        //Recreate tasklist and tasks
        $tasklist = $this->createTWTasklist($TW_milestone_id, $GH_milestone);
        $this->createTasks($GH_milestone['number'], $tasklist['TASKLISTID']);

        return ['milestone' => $TW_milestone, 'tasklist' => $tasklist];
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
      $GH_hooks = Github::repo()->hooks()->all($this->org, $this->repo);
      $TW_milestones = Teamwork::project($this->projectId)->milestones();

      $base_url = url();
      $relevant_hooks = [];

      foreach($GH_hooks as $hook) {
        //If the hook url is set
        if(isset($hook['config']['url'])){
          //Check that it matches this base url and append to $relevant_hooks
          if(strpos($hook['config']['url'], $base_url) !== FALSE){
            array_push($relevant_hooks, $hook);
          }
        }
      }

      foreach($GH_milestones as $GH_milestone) {
        $found = false;
        $title = $GH_milestone['title'];
        $number = $GH_milestone['number'];
        $tasklist_sync = ['found' => false, 'attached' => false];
        $tasks_sync = false;
        $syned = -1;

        //Check milestone existence by title
        foreach($TW_milestones['milestones'] as $TW_milestone) {
          if($TW_milestone['title'] === $title){
            $found = true;

            $tasklist_sync = $this->tasklistExists($TW_milestone);

            $tasks_sync = $this->tasksExist($GH_milestone, $tasklist_sync['id']);
          }
        }

        if($found || $tasklist_sync['synced'] || $tasks_sync['count_synced']) {
          if($found && $tasklist_sync['synced'] && $tasks_sync['count_synced']) {
            $synced = 1;
          } else {
            $synced = 0;
          }
        }

        array_push($syncs, [
          'title'             => $title, 
          'milestone_exists'  => $found, 
          'synced'            => $synced, 
          'number'            => $number,
          'tasklist'          => $tasklist_sync,
          'tasks'             => $tasks_sync
        ]);
      }

      //$milestone = Github::issues()->milestones()->show($this->org, $this->repo, $id);
    } catch (\RuntimeException $e) {
      //Milestone not found
      return (new Response(['message' => "Error: " . $e->getMessage()], 500));
    }

    return ['syncs' => $syncs, 'hooks' => $relevant_hooks];
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

  public function createTWTasklist($milestoneId, $GH_milestone) {
    return Teamwork::project($this->projectId)->createTasklist([
        'name'      => $GH_milestone['title'],
        'private'   => false,
        'pinned'    => true,
        'milestone-id'  => $milestoneId,
        'description'   => $GH_milestone['description']
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

  public function tasklistExists($TW_milestone) {
    $tasklists = Teamwork::project($this->projectId)->tasklists();
    $found = false;
    $attached = false;
    $tasklistId = null;

    foreach($tasklists['tasklists'] as $tasklist) {
      if($tasklist['name'] === $TW_milestone['title']) {
        $found = true;
        $tasklistId = $tasklist['id'];

        if($tasklist['milestone-id'] === $TW_milestone['id']) {
          $attached = true;
        }
      }
    }

    return ['found' => $found, 'attached' => $attached, 'id' => $tasklistId, 'synced' => ($found && $attached)];
  }

  public function tasksExist($GH_milestone, $tasklistId) {
    $openIssuesCount = $GH_milestone['open_issues'];
    $synced = false;

    $tasks = Teamwork::tasklist(intval($tasklistId))->tasks();
    $taskCount = count($tasks['todo-items']);

    if($openIssuesCount === $taskCount) {
      $synced = true;
    }

    return ['count_synced' => $synced];
  }

  public function createTasks($GH_number, $TW_tasklistId) {
    $params = [
      'milestone' => $GH_number,
    ];

    try {
      $client = Github::connection('main');

      $paginator = new ResultPager($client);

      $issues = $paginator->fetchAll($client->issues(), 'all', [$this->org, $this->repo, $params]);

      foreach($issues as $issue) {
        Teamwork::tasklist(intval($TW_tasklistId))->createTask([
          'content'     => $issue['title'],
          'notify'      => false,
          'description' => $issue['body'] . "\n\n***\n" . $issue['html_url'],
          'tags'        => 'Github Import'
        ]);
      }
    } catch (Exception $e) {
      return $e;
    }
    
    return $issues;
  }
}
