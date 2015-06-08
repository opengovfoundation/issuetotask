angular.module('app.controllers', [])
  .controller('AppController', function ($scope, $http) {

    $http.get('/api')
      .success(function (data) {
        $scope.repo_name = data.repo_name;
      });

    $http.get('/api/github/status')
      .success(function (data) {
        $scope.repo = data.repo;
      });

    $http.get('/api/teamwork/status')
      .success(function (data) {
        $scope.tasklist = data.tasklist;
      });
  });