angular.module('app.directives', [])
  .directive('milestoneProgress', function () {
    return {
      restrict: 'A',
      scope: {
        open_issues: '@openIssues',
        closed_issues: '@closedIssues'
      },
      templateUrl: '/templates/directives/milestoneProgress.html',
      controller: function ($scope) {
        var open = parseInt($scope.open_issues, 10);
        var closed = parseInt($scope.closed_issues, 10);

        $scope.total_issues = open + closed;

        $scope.progress = closed / (open + closed) * 100;
      }
    };
  });