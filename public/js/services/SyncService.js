angular.module('app.services')
  .factory('SyncService', function ($http, $rootScope) {
    var SyncService = {};

    SyncService.syncGithubMilestone = function (id) {
      return $http.post('/api/sync/github/milestone', {id: id})
        .success(function (data) {
          console.log(data);
          $rootScope.$broadcast('GithubMilestoneSyncUpdated', id);
        });
    };

    return SyncService;
  });