<?php namespace App;

use Rossedman\Teamwork\Facades\Teamwork;
use Rossedman\Teamwork\Task;

class TeamworkHelper {

  public function __construct() {
    $this->projectId = $_ENV['TW_PROJECT_ID'];
  }

  public function findTaskByName($name) {
    $tasks = Teamwork::project($this->projectId)->tasks();

    foreach($tasks['todo-items'] as $task) {
      if($task['content'] === $name) {
        return $task;
      }
    }

    return false;
  }

  public function closeTask(array $task) {
    $task = new Task();
    $task->id = $task['id'];
    return $task->complete();
  }
}