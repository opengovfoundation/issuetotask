angular.module('app.controllers', [])
  .controller('AppController', function ($scope, $http) {

    //Get Basic App Information
    $http.get('/api')
      .success(function (data) {
        $scope.repo_name = data.repo_name;
      });

    //Get GH Status Information
    $http.get('/api/github')
      .success(function (data) {
        $scope.repo = data.repo;
        $scope.milestones = data.milestones;
      });

    //Get Teamwork Status Information
    $http.get('/api/teamwork')
      .success(function (data) {
        $scope.project = data.project;
        $scope.tasklists = data.tasklists;
      });

  });