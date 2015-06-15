var imports = [
  'app.controllers',
  'app.directives',
  'app.filters',
  'app.services',
  'ui.router'
];

var app = angular.module('app', imports);

app.config(function ($locationProvider) {
  $locationProvider.html5Mode({
    enabled: true,
    requireBase: false
  });
});