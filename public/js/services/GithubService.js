angular.module('app.services')
  .factory('GithubService', function ($http, SessionService) {
    var GithubService = {};

    GithubService.getStatus = function () {
      return $http.get('/api/github')
        .success(function (data) {
          SessionService.setGithubStatus(data.repo, data.milestones, data.hooks);
        });
    };

    return GithubService;
  });