angular.module('app.services')
  .factory('SyncService', function ($http, $rootScope) {
    var SyncService = {};

    SyncService.syncGithubMilestone = function (number) {
      return $http.post('/api/sync/milestones', {number: number})
        .success(function (milestone) {
          $rootScope.$broadcast('GithubMilestoneSyncUpdated', milestone);
        })
        .error(function (data) {
          console.error(data);
        });
    };

    SyncService.getMilestoneStatuses = function () {
      return $http.get('/api/sync/milestones')
        .success(function (data) {
          console.log(data);
          $rootScope.$broadcast('GetGithubMilestoneSync', data);
        });
    };

    return SyncService;
  });