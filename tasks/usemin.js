var gulp = require('gulp');
var usemin = require('gulp-usemin');
var uglify = require('gulp-uglify');

gulp.task('usemin', ['rename'], function () {
  return gulp.src('./public/index.html')
    .pipe(usemin({
      js: [uglify({mangle: false})],
    }))
    .pipe(gulp.dest('public'));
});
