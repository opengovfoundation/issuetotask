angular.module('app.services')
  .factory('SyncService', function ($http, $rootScope) {
    var SyncService = {};

    SyncService.syncGithubMilestone = function (number) {
      return $http.post('/api/sync/milestone', {number: number})
        .success(function (milestone) {
          $rootScope.$broadcast('GithubMilestoneSyncUpdated', milestone);
        })
        .error(function (data) {
          console.error(data);
        });
    };

    return SyncService;
  });