angular.module('app')
  .config(function ($stateProvider, $urlRouterProvider) {
    $urlRouterProvider.otherwise('/');

    $stateProvider
      .state('index', {
        url: '/',
        controller: "GithubSyncController",
        templateUrl: '/templates/pages/github-sync.html'
      });
  });