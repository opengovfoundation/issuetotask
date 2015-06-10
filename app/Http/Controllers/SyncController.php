<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use GrahamCampbell\Github\Facades\GitHub;

use Illuminate\Http\Request;

class SyncController extends Controller {
  public function postSyncGithubMilestone() {
    $id = Input::get('id');

    return ['id' => $id];
  }
}
