var gulp = require('gulp');
var usemin = require('gulp-usemin');
var uglify = require('gulp-uglify');

gulp.task('usemin', function () {
  return gulp.src('./public/index.html')
    .pipe(usemin({
      options: {
        assetsDir: 'public/build'
      },
      js: [uglify()],
    }))
    .pipe(gulp.dest('public'));
});
