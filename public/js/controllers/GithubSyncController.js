angular.module('app.controllers')
  .controller('GithubSyncController', function ($scope, $http, $filter, GithubService, TeamworkService, SessionService, SyncService) {

    $scope.$on('GetGithubMilestoneSync', function (event, args) {
      $scope.syncs = args.syncs;
      console.log($scope.syncs);
    });

    $scope.$on('GithubMilestoneSynced', function () {
      SyncService.getMilestoneStatuses();
    });

    SyncService.getMilestoneStatuses();

    $scope.syncMilestone = function (sync) {
      SyncService.syncGithubMilestone(sync.number);
    };

    $scope.installWebhook = function () {
      console.log('installing webhook');
    };

    //Get GH Status Information
    //var github = GithubService.getStatus();

    //Get Teamwork Status Information
    //var teamwork = TeamworkService.getStatus();

    //Wait until both GH and TW are loaded
    // github.then(function () {
    //   teamwork.then(function () {
    //     //Load the GH and TW objects
    //     $scope.github = SessionService.getGithub();
    //     $scope.teamwork = SessionService.getTeamwork();

    //     $scope.webhook_installed = $scope.github.hooks.length > 0 ? true : false;

    //     $scope.milestone_syncs = [];

    //     angular.forEach($scope.github.milestones, function (milestone) {
    //       var found = $filter('milestoneMatch')(milestone, $scope.teamwork.dmilestones);
    //       $scope.milestone_syncs.push({title: milestone.title, found: found, number: milestone.number});
    //     });
    //   });
    // });
  });