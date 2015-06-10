angular.module('app.services')
  .factory('TeamworkService', function ($http, SessionService) {
    var TeamworkService = {};

    TeamworkService.getStatus = function () {
      return $http.get('/api/teamwork')
        .success(function (data) {
          SessionService.setTeamworkStatus(data.project, data.tasklists, data.milestones);
        });
    };

    return TeamworkService;
  });