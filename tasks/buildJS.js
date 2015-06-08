var gulp = require('gulp');
var cachebust = require('gulp-cache-bust');
var usemin = require('gulp-usemin');
var uglify = require('gulp-uglify');

gulp.task('buildJS', function () {
  return gulp.src('./public/index.html')
    .pipe(usemin({
      js: [uglify()],
    }))
    .pipe(cachebust({
      type: 'timestamp'
    }))
    .pipe(gulp.dest('public/build/'));
});
