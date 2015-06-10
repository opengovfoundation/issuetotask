angular.module('app.filters', [])
  .filter('milestoneMatch', function () {
    return function (milestone, tw_milestones) {
      var found = false;

      //Iterate over each task list name
      angular.forEach(tw_milestones, function (tw_milestone) {

        //If there's a match, return true
        if (angular.equals(milestone.title, tw_milestone.title)) {
          found = true;
        }
      });

      return found;
    };
  });