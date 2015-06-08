var gulp = require('gulp');
var cachebust = require('gulp-cache-bust');

gulp.task('cachebust', ['usemin'], function () {
  return gulp.src('./public/index.html')
    .pipe(cachebust({
      type: 'timestamp'
    }))
    .pipe(gulp.dest('public'));
});