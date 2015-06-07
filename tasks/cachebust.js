var gulp = require('gulp');
var cachebust = require('gulp-cache-bust');

gulp.task('cachebust', function () {
  gulp.src('./public/build/index.html')
    .pipe(cachebust({
      type: 'timestamp'
    }))
    .pipe(gulp.dest('./public/build'));
});