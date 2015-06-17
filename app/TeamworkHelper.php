<?php namespace App;

use Rossedman\Teamwork\Facades\Teamwork;

class TeamworkHelper {

  public function __construct() {
    $this->projectId = $_ENV['TW_PROJECT_ID'];
  }

  public function findTasklistByName($name) {
    
  }
}