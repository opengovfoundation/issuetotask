angular.module('app.controllers')
  .controller('GithubSyncController', function ($scope, $http, $filter, GithubService, TeamworkService, SessionService, SyncService, $timeout) {

    $scope.$on('GetGithubMilestoneSync', function (event, args) {
      $scope.syncs = args.syncs;
      $scope.webhook_installed = (args.hooks.length > 0);
    });

    $scope.$on('GithubMilestoneSynced', function () {
      SyncService.getMilestoneStatuses();
    });

    SyncService.getMilestoneStatuses();

    $scope.syncMilestone = function (sync) {
      sync.syncing = true;
      var promise = SyncService.syncGithubMilestone(sync.number);
      promise.then(function () {
        sync.syncing = false;
      });
    };

    $scope.installWebhook = function () {
      console.log('installing webhook');
    };
  });