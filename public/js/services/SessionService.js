angular.module('app.services')
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