angular.module('app.controllers', [])
  .controller('AppController', function ($scope, $http) {
    
    $http.get('/api')
      .success(function (data) {
        $scope.repo_name = data.repo_name;
      });
  });