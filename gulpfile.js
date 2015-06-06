var gulp = require('gulp'),
  gutil = require('gulp-util'),
  elixir = require('laravel-elixir'),
  usemin = require('gulp-usemin'),
  uglify = require('gulp-uglify'),
  rev = require('gulp-rev');


/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Less
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function (mix) {
  mix.less('app.less');
});

gulp.task('usemin', function () {
  gutil.log(gutil.colors.yellow('Building /public/index.html -> /public/build/index.html'));

  return gulp.src('./public/index.html')
    .pipe(usemin({
      js: [uglify(), rev()],
    }))
    .pipe(gulp.dest('public/build/'));
});