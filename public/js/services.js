angular.module('app.services', [])
  .factory('GithubService', function ($http, SessionService) {
    var GithubService = {};

    GithubService.getStatus = function () {
      return $http.get('/api/github')
        .success(function (data) {
          SessionService.setGithubStatus(data.repo, data.milestones, data.hooks);
        });
    };

    return GithubService;
  })
  .factory('TeamworkService', function ($http, SessionService) {
    var TeamworkService = {};

    TeamworkService.getStatus = function () {
      return $http.get('/api/teamwork')
        .success(function (data) {
          SessionService.setTeamworkStatus(data.project, data.tasklists, data.milestones);
        });
    };

    return TeamworkService;
  })
  .service('SessionService', function () {
    this.Github = {};
    this.Teamwork = {};

    this.setGithubStatus = function (repo, milestones, hooks) {
      this.Github.repo = repo;
      this.Github.milestones = milestones;
      this.Github.hooks = hooks;
    };

    this.getGithub = function () {
      return this.Github;
    };

    this.setTeamworkStatus = function (project, tasklists, milestones) {
      this.Teamwork.project = project;
      this.Teamwork.tasklists = tasklists;
      this.Teamwork.milestones = milestones;
    };

    this.getTeamwork = function () {
      return this.Teamwork;
    };

    return this;
  });