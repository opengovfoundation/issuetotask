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
  });