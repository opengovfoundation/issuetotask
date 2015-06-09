angular.module('app.filters', [])
  .filter('milestoneMatch', function () {
    return function (milestone, tasklists) {
      var found = false;

      //Iterate over each task list name
      angular.forEach(tasklists, function (list) {

        //If there's a match, return true
        if (angular.equals(milestone.title, list.name)) {
          found = true;
        }
      });

      return found;
    };
  });