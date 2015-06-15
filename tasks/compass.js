var gulp = require('gulp');
var compass = require('gulp-compass');

gulp.task('compass', function () {
  return gulp.src('./public/sass/*.scss')
    .pipe(compass({config_file: './public/config.rb'}))
    .pipe(gulp.dest('./public/css'));
});