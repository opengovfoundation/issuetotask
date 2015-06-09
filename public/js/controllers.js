angular.module('app.controllers', [])
  .controller('AppController', function ($scope, $http, $filter) {

    //Get Basic App Information
    $http.get('/api')
      .success(function (data) {
        $scope.repo_name = data.repo_name;
      });

    //Get GH Status Information
    var github = $http.get('/api/github')
      .success(function (data) {
        $scope.repo = data.repo;
        $scope.milestones = data.milestones;
      });

    //Get Teamwork Status Information
    var teamwork = $http.get('/api/teamwork')
      .success(function (data) {
        $scope.project = data.project;
        $scope.tasklists = data.tasklists;
        $scope.tw_milestones = data.milestones;
      });

    var hooks = $http.get('/api/github/hooks')
      .success(function (data) {
        $scope.hooks = data.hooks;
      });

    //Wait until both GH and TW are loaded
    github.then(function () {
      teamwork.then(function () {
        $scope.milestone_syncs = [];

        angular.forEach($scope.milestones, function (milestone) {
          var found = $filter('milestoneMatch')(milestone, $scope.tasklists);
          $scope.milestone_syncs.push({title: milestone.title, found: found});
        });
      });
    });

  });