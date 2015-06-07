var elixir = require('laravel-elixir'),
  gulp = require('gulp'),
  gutil = require('gulp-util'),
  requireDir = require('require-dir');

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

requireDir('./tasks');

//Default Task
gulp.task('default', ['build'], function () {
  gutil.log(gutil.colors.yellow('Building production files...'));
});

//Build Task
gulp.task('build', ['usemin', 'cachebust'], function () {
  gutil.log(gutil.colors.yellow('Running usemin task...'));
});