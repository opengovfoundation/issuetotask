var gulp = require('gulp');
var rm = require('gulp-rm');

gulp.task('cleanup', ['build'], function () {
  return gulp.src('public/index.html')
    .pipe(rm());
});