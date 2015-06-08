var gulp = require('gulp');
var rename = require('gulp-rename');

gulp.task('rename', function () {
  return gulp.src('public/pre-build.html')
    .pipe(rename('public/index.html'))
    .pipe(gulp.dest('.'));
});